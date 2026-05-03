@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">My Leaves</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">My Leaves</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage your leave applications and view real-time time-off balances.</p>
        </div>
        <div class="mt-4 flex sm:ml-4 sm:mt-0">
            <a href="{{ route('leaves.create') }}" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Apply for Leave
            </a>
        </div>
    </div>

    <!-- Leave Balances -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @foreach(auth()->user()->leaveBalances()->where('year', now()->year)->get() as $balance)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-md p-4 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">{{ $balance->leaveType->name }}</p>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $balance->available_days }}</span>
                <span class="text-xs font-semibold text-slate-400">/ {{ $balance->accrued_days }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- History Table -->
    <div class="overflow-hidden bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Application History</h3>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-white dark:bg-slate-900">
                    <tr>
                        <th scope="col" class="py-3 pl-6 pr-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Category</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Duration</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Reason</th>
                        <th scope="col" class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($leaveRequests as $leave)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-bold text-slate-900 dark:text-white">
                            {{ $leave->leaveType->name }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400">
                            {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                            @if($leave->is_half_day)
                                <span class="ml-2 inline-flex items-center rounded bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 text-[9px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider border border-slate-200 dark:border-slate-700">Half Day</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate">
                            {{ $leave->reason }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-center">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider 
                                {{ $leave->status === 'Approved' ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800' : 
                                   ($leave->status === 'Rejected' ? 'text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-950/30 border border-red-200 dark:border-red-800' : 
                                   'text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800') }}">
                                {{ $leave->status }}
                            </span>
                            @if($leave->approver_note)
                                <div class="mt-1 text-[10px] text-slate-500 italic max-w-[200px] truncate" title="{{ $leave->approver_note }}">
                                    Note: {{ $leave->approver_note }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if($leaveRequests->isEmpty())
                        <tr><td colspan="4" class="py-12 text-center text-sm text-slate-400 italic">No leave applications found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
