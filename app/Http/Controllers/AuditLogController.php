<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            })->orWhere('action', 'ilike', "%{$search}%")
                ->orWhere('model_type', 'ilike', "%{$search}%");
        }

        $logs = $query->latest()->paginate(50)->withQueryString();

        return view('audit-logs.index', compact('logs'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('audit-logs.show', compact('auditLog'));
    }
}
