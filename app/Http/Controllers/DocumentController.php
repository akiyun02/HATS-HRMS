<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentReviewed;
use App\Notifications\DocumentUploaded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    public function store(Request $request, User $user)
    {
        // Basic authorization
        if (! auth()->user()->hasAnyRole(['HR', 'Admin']) && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // If the request is POST but all input is empty, post_max_size was likely exceeded
        if ($request->isMethod('post') && empty($request->all()) && $request->headers->get('Content-Length') > 0) {
            return back()->with('error', 'The upload failed because the file is too large for the server. Please contact your administrator to increase post_max_size in php.ini.');
        }

        try {
            // Check for PHP upload errors before validation
            if ($request->hasFile('document') && $request->file('document')->getError() !== UPLOAD_ERR_OK) {
                $error = match ($request->file('document')->getError()) {
                    UPLOAD_ERR_INI_SIZE => 'The file is larger than the server allows (max 10MB).',
                    UPLOAD_ERR_PARTIAL => 'The file was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    default => 'Upload failed with error code: '.$request->file('document')->getError(),
                };

                return back()->with('error', $error);
            }

            $validated = $request->validate([
                'document_name' => 'required|string|max:255',
                'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|extensions:pdf,jpg,jpeg,png,doc,docx|max:10240',
                'type' => 'nullable|string',
            ]);

            if (! $request->hasFile('document')) {
                return back()->with('error', 'No file was received by the server. Please ensure the file is not too large for the system.');
            }

            $file = $request->file('document');
            if (! $file->isValid()) {
                return back()->with('error', 'The uploaded file is not valid: '.$file->getErrorMessage());
            }

            $path = $file->store('documents/'.$user->id, 'public');
            $status = auth()->user()->hasAnyRole(['HR', 'Admin']) ? 'Approved' : 'Pending';

            $document = Document::create([
                'user_id' => $user->id,
                'name' => $validated['document_name'],
                'file_path' => $path,
                'type' => $request->input('type'),
                'status' => $status,
            ]);

            // Notify HR if it's an employee upload - Wrapped in try/catch to prevent blocking
            if ($status === 'Pending') {
                try {
                    $hrUsers = User::whereHas('roles', function ($q) {
                        $q->whereIn('name', ['HR', 'Admin']);
                    })->get();
                    Notification::send($hrUsers, new DocumentUploaded($document));
                } catch (\Exception $e) {
                    // Log but don't fail the upload
                    Log::error('Document notification failed: '.$e->getMessage());
                }
            }

            return back()->with('success', 'Document uploaded successfully.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: '.$e->getMessage());
        }
    }

    public function approve(Document $document)
    {
        if (! auth()->user()->hasAnyRole(['HR', 'Admin'])) {
            abort(403);
        }

        $document->update(['status' => 'Approved']);
        $document->user->notify(new DocumentReviewed($document));

        return back()->with('success', 'Document approved.');
    }

    public function reject(Document $document)
    {
        if (! auth()->user()->hasAnyRole(['HR', 'Admin'])) {
            abort(403);
        }

        $document->update(['status' => 'Rejected']);
        $document->user->notify(new DocumentReviewed($document));

        return back()->with('success', 'Document rejected.');
    }

    public function destroy(Document $document)
    {
        // Only HR/Admin or the document owner can delete.
        if (! auth()->user()->hasAnyRole(['HR', 'Admin']) && auth()->id() !== $document->user_id) {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document removed.');
    }
}
