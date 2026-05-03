@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">System Settings</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Configure global organizational policies and access control roles.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-emerald-50 dark:bg-emerald-900/20 p-4 border border-emerald-200 dark:border-emerald-800">
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
            <p class="text-sm font-bold text-red-800 dark:text-red-400">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Global Policies & Statutory -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Global Configuration</h3>
                </div>
                <form action="{{ route('settings.update') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" value="{{ \App\Models\Setting::get('company.name') }}" required class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Shift Start</label>
                            <input type="time" name="attendance_start" value="{{ \App\Models\Setting::get('attendance.start_time') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Shift End</label>
                            <input type="time" name="attendance_end" value="{{ \App\Models\Setting::get('attendance.end_time') }}" required class="form-input">
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">SSS Settings</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Emp. Rate (%)</label>
                                <input type="number" step="0.01" name="sss_rate" value="{{ \App\Models\Setting::get('statutory.sss_rate', 4.5) }}" required class="form-input">
                            </div>
                            <div>
                                <label class="form-label">MSC Cap (₱)</label>
                                <input type="number" step="0.01" name="sss_msc_cap" value="{{ \App\Models\Setting::get('statutory.sss_msc_cap', 30000) }}" required class="form-input">
                            </div>
                        </div>

                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">PhilHealth Settings</h4>
                        <div class="mb-4">
                            <label class="form-label">Emp. Rate (%)</label>
                            <input type="number" step="0.01" name="philhealth_rate" value="{{ \App\Models\Setting::get('statutory.philhealth_rate', 2.5) }}" required class="form-input">
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Floor (₱)</label>
                                <input type="number" step="0.01" name="philhealth_msc_floor" value="{{ \App\Models\Setting::get('statutory.philhealth_msc_floor', 10000) }}" required class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Ceiling (₱)</label>
                                <input type="number" step="0.01" name="philhealth_msc_ceiling" value="{{ \App\Models\Setting::get('statutory.philhealth_msc_ceiling', 100000) }}" required class="form-input">
                            </div>
                        </div>

                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">Pag-IBIG Settings</h4>
                        <div class="mb-4">
                            <label class="form-label">Fixed Amount (₱)</label>
                            <input type="number" step="0.01" name="pagibig_amount" value="{{ \App\Models\Setting::get('statutory.pagibig_amount', 200) }}" required class="form-input">
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-md bg-brand-600 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                        Save Configurations
                    </button>
                </form>
            </div>

            <!-- Role Management -->
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Access Roles</h3>
                    <button onclick="document.getElementById('role-modal').classList.remove('hidden')" class="text-[10px] font-bold text-brand-600 uppercase">New Role</button>
                </div>
                <div class="p-0">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 table-zebra">
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($roles as $role)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3 pl-6">
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $role->name }}</div>
                                </td>
                                <td class="py-3 pr-6 text-right">
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete role?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Leave Types CRUD -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Leave Categories</h3>
                    </div>
                    <button onclick="document.getElementById('leave-modal').classList.remove('hidden')" class="rounded-md bg-brand-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">Add Category</button>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra">
                        <thead class="bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th class="py-3 pl-6 text-left text-[10px] font-bold uppercase text-slate-500 tracking-wider">Name</th>
                                <th class="py-3 px-4 text-left text-[10px] font-bold uppercase text-slate-500 tracking-wider">Yearly Limit</th>
                                <th class="py-3 px-4 text-left text-[10px] font-bold uppercase text-slate-500 tracking-wider">Description</th>
                                <th class="py-3 pr-6 text-right text-[10px] font-bold uppercase text-slate-500 tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                            @foreach($leaveTypes as $type)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="py-4 pl-6 text-sm font-bold text-slate-900 dark:text-white">{{ $type->name }}</td>
                                <td class="py-4 px-4 text-sm font-semibold text-brand-600 dark:text-brand-400">{{ $type->max_days }} Days</td>
                                <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate">{{ $type->description ?? '—' }}</td>
                                <td class="py-4 pr-6 text-right">
                                    <form action="{{ route('leave-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Delete this leave category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-md text-slate-400 hover:text-red-600 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($leaveTypes->isEmpty())
                            <tr><td colspan="4" class="py-12 text-center text-sm text-slate-400 italic">No leave categories defined.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Type Modal -->
<div id="leave-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">New Leave Category</h3>
            <button onclick="document.getElementById('leave-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('leave-types.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="form-label">Category Name</label>
                <input type="text" name="name" required class="form-input" placeholder="e.g. Sabbatical">
            </div>
            <div>
                <label class="form-label">Yearly Allowance (Days)</label>
                <input type="number" name="max_days" required class="form-input" placeholder="0">
            </div>
            <div>
                <label class="form-label">Policy Description</label>
                <textarea name="description" rows="3" class="form-input" placeholder="Brief details about this leave type..."></textarea>
            </div>
            <button type="submit" class="w-full bg-brand-600 text-white py-2.5 rounded-md font-bold text-sm shadow-sm hover:bg-brand-700 transition-colors">Save Category</button>
        </form>
    </div>
</div>

<!-- Role Modal -->
<div id="role-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">New System Role</h3>
            <button onclick="document.getElementById('role-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('roles.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="form-label">Role Title</label>
                <input type="text" name="name" required class="form-input" placeholder="e.g. Supervisor">
            </div>
            <div>
                <label class="form-label">Scope Description</label>
                <textarea name="description" rows="3" class="form-input" placeholder="What permissions does this role have?"></textarea>
            </div>
            <button type="submit" class="w-full bg-brand-600 text-white py-2.5 rounded-md font-bold text-sm shadow-sm hover:bg-brand-700 transition-colors">Save Role</button>
        </form>
    </div>
</div>
@endsection
