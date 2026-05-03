@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Leaves</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Leaves</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Review and process staff leave applications and balance adjustments.</p>
        </div>
    </div>

    <form id="bulk-action-form" action="{{ route('leaves.bulk-action') }}" method="POST" class="space-y-8">
        @csrf
        <!-- Pending Requests -->
        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden relative">
            <!-- Bulk Action Bar -->
            <div id="bulk-bar" class="hidden sticky top-0 z-20 bg-brand-600 text-white px-6 py-3 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-4">
                    <span id="selected-count" class="text-xs font-bold uppercase tracking-wider bg-white/10 px-2 py-1 rounded">0 Selected</span>
                    <input type="text" name="approver_note" placeholder="Optional bulk note..." class="bg-white/10 border-white/20 rounded-md text-xs placeholder:text-white/60 focus:ring-white/40 focus:border-white/40 min-w-[250px] py-1 px-3">
                </div>
                <div class="flex gap-2">
                    <button type="submit" name="action" value="approve" class="bg-white text-brand-600 px-4 py-1.5 rounded text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors">Approve</button>
                    <button type="submit" name="action" value="reject" class="bg-red-500 text-white px-4 py-1.5 rounded text-xs font-bold uppercase tracking-wider hover:bg-red-600 transition-colors border border-white/10">Reject</button>
                </div>
            </div>

            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Pending Applications</h3>
            </div>
            <div class="min-w-full align-middle">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra">
                    <thead class="bg-white dark:bg-slate-900 text-left">
                        <tr>
                            <th scope="col" class="py-3 pl-6 pr-3 w-10">
                                <input type="checkbox" id="select-all" class="rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 dark:bg-slate-800">
                            </th>
                            <th scope="col" class="py-3 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Employee</th>
                            <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</th>
                            <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Dates</th>
                            <th scope="col" class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Days</th>
                            <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Reason</th>
                            <th scope="col" class="py-3 pr-6 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                        @foreach($leaveRequests as $leave)
                        @php
                            $days = $leave->start_date->diffInDays($leave->end_date) + 1;
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="py-4 pl-6 pr-3">
                                <input type="checkbox" name="ids[]" value="{{ $leave->id }}" class="row-checkbox rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 dark:bg-slate-800">
                            </td>
                            <td class="whitespace-nowrap py-4 px-3">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-xs mr-3">
                                        {{ substr($leave->user->name, 0, 1) }}
                                    </div>
                                    <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $leave->user->name }}</div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs font-bold text-slate-600 dark:text-slate-400 uppercase">{{ $leave->leaveType->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400">
                                {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-center">
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                    {{ $days }} {{ Str::plural('Day', $days) }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate">{{ $leave->reason }}</td>
                            <td class="py-4 pr-6 text-right">
                                <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('leaves.admin.edit', $leave) }}" title="Edit Request" class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-500 hover:bg-slate-100 transition-colors border border-slate-200 dark:border-slate-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </a>
                                    <button type="button" onclick="openDecisionModal({{ $leave->id }}, '{{ $leave->user->name }}', 'approve')" title="Approve" class="p-1.5 rounded-md bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 hover:bg-emerald-100 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button type="button" onclick="openDecisionModal({{ $leave->id }}, '{{ $leave->user->name }}', 'reject')" title="Reject" class="p-1.5 rounded-md bg-red-50 dark:bg-red-950/30 text-red-600 hover:bg-red-100 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($leaveRequests->isEmpty())
                        <tr>
                            <td colspan="7" class="py-12 text-center text-sm text-slate-400 italic">No pending applications</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- History -->
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Recent Decisions</h3>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-white dark:bg-slate-900">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Employee</th>
                        <th scope="col" class="px-3 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Dates</th>
                        <th scope="col" class="px-3 py-3.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                        <th scope="col" class="px-3 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Approver Note</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 pr-6">Processed By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($history as $leave)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3">
                            <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $leave->user->name }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $leave->leaveType->name }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400">
                            {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-center">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide 
                                {{ $leave->status === 'Approved' ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800' : 'text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-950/30 border border-red-200 dark:border-red-800' }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400 italic max-w-xs truncate">
                            {{ $leave->approver_note ?? '—' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs text-slate-500 dark:text-slate-500 text-right pr-6 font-bold">
                            {{ $leave->approver->name ?? 'System' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Individual Decision Modal -->
<div id="decision-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) closeDecisionModal()">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 flex justify-between items-center">
            <h3 id="modal-title" class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Process Decision</h3>
            <button onclick="closeDecisionModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="decision-form" method="POST" class="p-6 space-y-4">
            @csrf
            <p id="modal-desc" class="text-sm text-slate-600 dark:text-slate-400 font-medium"></p>
            <div>
                <label for="modal_approver_note" class="form-label">Decision Note (Optional)</label>
                <textarea name="approver_note" id="modal_approver_note" rows="3" class="form-input" placeholder="Explain your decision..."></textarea>
            </div>
            <button type="submit" id="modal-submit" class="w-full py-2.5 rounded-md font-bold uppercase text-xs tracking-wider shadow-sm transition-all">Confirm Action</button>
        </form>
    </div>
</div>

<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const bulkBar = document.getElementById('bulk-bar');
    const countSpan = document.getElementById('selected-count');

    function updateBulkBar() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if(countSpan) countSpan.textContent = `${checkedCount} Selected`;
        if (checkedCount > 0) {
            bulkBar?.classList.remove('hidden');
        } else {
            bulkBar?.classList.add('hidden');
        }
    }

    if(selectAll) {
        selectAll.addEventListener('change', (e) => {
            checkboxes.forEach(cb => cb.checked = e.target.checked);
            updateBulkBar();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });

    const modal = document.getElementById('decision-modal');
    const form = document.getElementById('decision-form');
    const title = document.getElementById('modal-title');
    const desc = document.getElementById('modal-desc');
    const submit = document.getElementById('modal-submit');

    function openDecisionModal(id, employeeName, action) {
        const baseUrl = "{{ url('admin/leaves') }}";
        form.action = `${baseUrl}/${id}/${action}`;
        
        if (action === 'approve') {
            title.textContent = 'Approve Leave';
            desc.textContent = `Confirm approval of leave request for ${employeeName}.`;
            submit.className = 'w-full bg-emerald-600 text-white py-2.5 rounded-md font-bold uppercase text-xs tracking-wider shadow-sm hover:bg-emerald-700 transition-colors';
            submit.textContent = 'Approve Request';
        } else {
            title.textContent = 'Reject Leave';
            desc.textContent = `Confirm rejection of leave request for ${employeeName}.`;
            submit.className = 'w-full bg-red-600 text-white py-2.5 rounded-md font-bold uppercase text-xs tracking-wider shadow-sm hover:bg-red-700 transition-colors';
            submit.textContent = 'Reject Request';
        }
        
        modal.classList.remove('hidden');
    }

    function closeDecisionModal() {
        modal.classList.add('hidden');
        form.reset();
    }
</script>
@endsection
