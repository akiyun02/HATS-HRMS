@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 text-2xl font-bold shadow-sm">
                {{ substr($employee->name, 0, 1) }}
            </div>
                    <div>
                <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">{{ $employee->name }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-semibold mt-0.5">
                    <span class="text-brand-600 uppercase tracking-wider">{{ $employee->employeeProfile?->jobRole?->name ?? 'Position Not Assigned' }}</span>
                    <span class="mx-2 text-slate-300 dark:text-slate-700">•</span>
                    <span class="text-slate-400">ID: {{ $employee->employeeProfile?->employee_id ?? 'N/A' }}</span>
                    <span class="mx-2 text-slate-300 dark:text-slate-700">•</span>
                    <span class="text-slate-400 uppercase tracking-tighter">Policy: {{ $employee->employeeProfile?->leavePolicy?->name ?? 'None' }}</span>
                </p>
            </div>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Edit Profile
            </a>
            <a href="{{ route('performance.admin', ['user_id' => $employee->id]) }}" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                Conduct Review
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Professional Profile</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Primary Email</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Direct Phone</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->employeeProfile?->phone ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Gender</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->employeeProfile?->gender ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Birthday</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->employeeProfile?->birthday?->format('F d, Y') ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Marital Status</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->employeeProfile?->marital_status ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Organization</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->employeeProfile?->jobRole?->department?->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Service Start</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ $employee->employeeProfile?->joining_date?->format('F d, Y') ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Residential Address</dt>
                        <dd class="text-sm font-medium text-slate-600 dark:text-slate-400 leading-relaxed">{{ $employee->employeeProfile?->address ?? 'Not provided' }}</dd>
                    </div>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Attendance Logs</h3>
                    <a href="{{ route('attendance.admin', ['search' => $employee->name]) }}" class="text-[10px] font-bold text-brand-600 uppercase tracking-widest hover:text-brand-500 transition-colors">Full Logs &rarr;</a>
                </div>
                <div class="min-w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                        <thead class="bg-white dark:bg-slate-900">
                            <tr>
                                <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Date</th>
                                <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider text-center">Schedule</th>
                                <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                            @forelse($employee->attendances->sortByDesc('date')->take(10) as $attendance)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <td class="whitespace-nowrap py-4 px-6 text-sm font-bold text-slate-900 dark:text-white">{{ $attendance->date->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-xs font-semibold text-slate-500 text-center uppercase">
                                    {{ $attendance->clock_in?->format('h:i A') ?? '--' }} <span class="mx-1 text-slate-300">/</span> {{ $attendance->clock_out?->format('h:i A') ?? '--' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide border 
                                        {{ $attendance->status === 'Present' ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 
                                           ($attendance->status === 'Late' ? 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400' : 
                                           'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400') }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-10 text-center text-xs text-slate-400 italic">No attendance records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 201 File (Documents) -->
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Employee 201 File</h3>
                    @can('hr')
                    <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-brand-600 text-white px-4 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wider shadow-sm hover:bg-brand-700 transition-colors">Add Document</button>
                    @endcan
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                        <thead>
                            <tr class="bg-white dark:bg-slate-900">
                                <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Name</th>
                                <th class="py-3 px-3 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Status</th>
                                <th class="py-3 pr-6 text-right text-[10px] font-bold uppercase text-slate-500 tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                            @foreach($employee->documents as $doc)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $doc->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $doc->type ?? 'General' }}</div>
                                </td>
                                <td class="py-4 px-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide border 
                                        {{ $doc->status === 'Approved' ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : ($doc->status === 'Rejected' ? 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400' : 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400') }}">
                                        {{ $doc->status }}
                                    </span>
                                </td>
                                <td class="py-4 pr-6 text-right">
                                    <div class="flex justify-end gap-2 items-center">
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-tighter">View</a>
                                        @if(auth()->user()->hasAnyRole(['HR', 'Admin']) && $doc->status === 'Pending')
                                        <form action="{{ route('documents.approve', $doc) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-700 font-bold uppercase text-[10px]">Approve</button>
                                        </form>
                                        <form action="{{ route('documents.reject', $doc) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-700 font-bold uppercase text-[10px]">Reject</button>
                                        </form>
                                        @endif
                                        @can('hr')
                                        <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Remove document?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 rounded-md text-slate-400 hover:text-red-600 transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($employee->documents->isEmpty())
                            <tr>
                                <td colspan="3" class="py-12 text-center text-xs text-slate-400 italic font-medium tracking-tight">No documents in 201 file.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Leave Entitlement</h3>
                    <div class="flex gap-2">
                        <button onclick="document.getElementById('history-modal').classList.remove('hidden')" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-brand-600 transition-colors">History</button>
                        @can('hr')
                        <button onclick="document.getElementById('balance-modal').classList.remove('hidden')" class="text-[10px] font-bold text-brand-600 uppercase tracking-widest hover:text-brand-500 transition-colors">Adjust</button>
                        @endcan
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    @forelse($employee->leaveBalances->where('year', now()->year) as $balance)
                    <div class="group/balance relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $balance->leaveType->name }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $balance->available_days }} <span class="text-slate-400">/ {{ $balance->accrued_days }}</span></span>
                                @can('hr')
                                <form action="{{ route('leave-balances.destroy', [$employee, $balance]) }}" method="POST" onsubmit="return confirm('Remove this leave category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="opacity-0 group-hover/balance:opacity-100 text-red-500 hover:text-red-600 transition-all">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            @php
                                $percent = $balance->accrued_days > 0 ? ($balance->available_days / $balance->accrued_days) * 100 : 0;
                            @endphp
                            <div class="bg-brand-600 h-full rounded-full shadow-sm" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-xs text-slate-400 italic">No leave balances recorded for {{ now()->year }}.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-brand-600 rounded-lg p-6 text-white shadow-sm flex flex-col justify-between group">
                <h3 class="text-xs font-bold opacity-80 uppercase tracking-wider mb-6">Emergency Contact</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase opacity-60">Primary Guardian</p>
                        <p class="text-lg font-bold tracking-tight">{{ $employee->employeeProfile?->emergency_contact_name ?? 'Not Registered' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase opacity-60">Direct Phone</p>
                        <p class="text-sm font-bold opacity-90">{{ $employee->employeeProfile?->emergency_contact_phone ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-white/10">
                    <p class="text-[10px] font-bold uppercase opacity-60 leading-relaxed">Ensure contact details are validated during every annual profile audit.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Balance Adjustment Modal -->
<div id="balance-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Adjust Entitlement</h3>
            <button onclick="document.getElementById('balance-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('leave-balances.update', $employee) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="form-label">Leave Category</label>
                <select name="leave_type_id" required class="form-input">
                    @foreach(\App\Models\LeaveType::all() as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="form-label">Adjustment Amount (Days)</label>
                <input type="number" name="accrued_days" step="0.5" required class="form-input" placeholder="0.0">
                <p class="mt-1 text-[10px] text-slate-500 italic">Use positive numbers to add days, negative to subtract.</p>
            </div>

            <div>
                <label class="form-label">Reason for Adjustment</label>
                <textarea name="reason" required class="form-input" rows="2" placeholder="e.g. Correction of initial entitlement, Performance bonus leave, etc."></textarea>
                <div class="mt-2 flex flex-wrap gap-1">
                    <button type="button" onclick="document.getElementsByName('reason')[0].value='Correction of initial entitlement'" class="text-[9px] px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 transition-colors">Correction</button>
                    <button type="button" onclick="document.getElementsByName('reason')[0].value='Performance bonus leave'" class="text-[9px] px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 transition-colors">Performance Bonus</button>
                    <button type="button" onclick="document.getElementsByName('reason')[0].value='Carry-over adjustment'" class="text-[9px] px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 transition-colors">Carry-over</button>
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="document.getElementById('balance-modal').classList.add('hidden')" class="flex-1 py-2 text-sm font-bold text-slate-500 border border-slate-200 dark:border-slate-700 rounded-md hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 bg-brand-600 text-white py-2 rounded-md text-sm font-bold shadow-sm hover:bg-brand-700 transition-colors">Adjust Balance</button>
            </div>
        </form>
    </div>
</div>

    </div>
</div>

<!-- History Modal -->
<div id="history-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Leave Ledger History</h3>
            <button onclick="document.getElementById('history-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-0 overflow-y-auto max-h-[500px]">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50 sticky top-0">
                    <tr>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase text-center">Amount</th>
                        <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @forelse($employee->leaveLedgerEntries()->with('leaveType')->latest()->get() as $entry)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-900 dark:text-white">{{ $entry->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase border 
                                {{ $entry->type === 'allocation' ? 'border-brand-100 bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-400' : 
                                   ($entry->type === 'adjustment' ? 'border-amber-100 bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400' : 
                                   'border-slate-100 bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-400') }}">
                                {{ $entry->type }}
                            </span>
                            <div class="text-[9px] text-slate-400 font-bold uppercase mt-1">{{ $entry->leaveType->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold {{ $entry->amount > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $entry->amount > 0 ? '+' : '' }}{{ $entry->amount }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ $entry->description }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-12 text-center text-sm text-slate-400 italic">No ledger transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-end">
            <button onclick="document.getElementById('history-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 uppercase tracking-widest hover:text-slate-900 transition-colors">Close</button>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="upload-modal" class="fixed inset-0 z-[100] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Upload 201 Document</h3>
            <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('documents.store', $employee) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
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
