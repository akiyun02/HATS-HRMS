@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
            <div class="min-w-0 flex-1">
                <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">My Profile</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage your personal information, security, and employment documents.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-md bg-emerald-50 dark:bg-emerald-900/20 p-4 border border-emerald-200 dark:border-emerald-800">
                <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Profile Form -->
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Personal Details</h3>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-input">
                        @error('name') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Primary Email (Read-only)</label>
                        <input type="email" value="{{ $user->email }}" readonly class="form-input bg-slate-50 dark:bg-slate-950 text-slate-500 cursor-not-allowed border-dashed ring-0">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->employeeProfile?->phone ?? '') }}" class="form-input" placeholder="e.g. +1 555-0123">
                        </div>
                        <div>
                            <label for="emergency_contact_phone" class="form-label">Emergency Phone</label>
                            <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->employeeProfile?->emergency_contact_phone ?? '') }}" class="form-input" placeholder="e.g. +1 555-9999">
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full rounded-md bg-brand-600 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Employment & Security -->
            <div class="space-y-8">
                <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg p-6 relative overflow-hidden group">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-wider">Employment Information</h3>
                    <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Employee ID</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user->employeeProfile?->employee_id ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Service Start</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user->employeeProfile?->joining_date?->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Department</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user->employeeProfile?->jobRole?->department?->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Current Role</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user->employeeProfile?->jobRole?->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Account Security</h3>
                    </div>
                    <form action="{{ route('profile.password') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="password" name="current_password" placeholder="Current Password" required class="form-input">
                        <input type="password" name="password" placeholder="New Secure Password" required class="form-input">
                        <input type="password" name="password_confirmation" placeholder="Confirm New Password" required class="form-input">
                        <button type="submit" class="w-full rounded-md bg-slate-900 dark:bg-white text-white dark:text-slate-900 py-2.5 text-sm font-bold transition-colors uppercase tracking-wider">
                            Update Password
                        </button>
                    </form>
                </div>

                <!-- 201 File (Personal Documents) -->
                <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">My 201 File</h3>
                        <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-brand-600 text-white px-4 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wider shadow-sm hover:bg-brand-700 transition-colors">Upload Document</button>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 table-zebra">
                            <thead>
                                <tr class="bg-slate-50/30 dark:bg-slate-800/30">
                                    <th class="py-3 pl-6 text-left text-[10px] font-bold uppercase text-slate-500 tracking-wider">Name</th>
                                    <th class="py-3 px-3 text-left text-[10px] font-bold uppercase text-slate-500 tracking-wider">Status</th>
                                    <th class="py-3 pr-6 text-right text-[10px] font-bold uppercase text-slate-500 tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($user->documents as $doc)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="py-3.5 pl-6">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $doc->name }}</div>
                                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase">{{ $doc->type ?? 'General' }}</div>
                                        </td>
                                        <td class="py-3.5 px-3">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide 
                                                {{ $doc->status === 'Approved' ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400' : ($doc->status === 'Rejected' ? 'text-red-700 bg-red-50 dark:text-red-400' : 'text-brand-700 bg-brand-50 dark:text-brand-400') }}">
                                                {{ $doc->status }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 pr-6 text-right">
                                            <div class="flex justify-end gap-2 items-center">
                                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-xs font-bold text-brand-600 hover:text-brand-700">View</a>
                                                @if($doc->status === 'Pending')
                                                    <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Remove document?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-1 rounded-md text-slate-400 hover:text-red-600 transition-colors">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($user->documents->isEmpty())
                                    <tr><td colspan="3" class="py-10 text-center text-xs text-slate-400 italic">No documents uploaded.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="upload-modal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Upload 201 Document</h3>
                <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('documents.store', $user) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <div>
                    <label for="document_name" class="form-label">Document Title</label>
                    <input type="text" name="document_name" id="document_name" required class="form-input" placeholder="e.g. Passport Copy">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <select name="type" class="form-input">
                        <option value="ID/Passport">ID / Passport</option>
                        <option value="Certificate">Certificate</option>
                        <option value="Contract">Contract</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="document" class="form-label">File Selection</label>
                    <input type="file" name="document" id="document" required accept="application/pdf,image/*,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 transition-all cursor-pointer">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="flex-1 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 bg-brand-600 text-white py-2 rounded-md text-sm font-bold shadow-sm hover:bg-brand-700 transition-all">Upload Now</button>
                </div>
            </form>
        </div>
    </div>
@endsection