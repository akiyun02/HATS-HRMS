@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Attendance</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Daily Attendance</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage your daily clock sessions and verify shift history.</p>
        </div>
        <div class="mt-4 flex sm:ml-4 sm:mt-0 gap-3">
            <button onclick="document.getElementById('correction-modal').classList.remove('hidden')" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Request Correction
            </button>
            @if(!$todayAttendance)
                <form action="{{ route('attendance.clock-in') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-md bg-brand-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                        Clock In
                    </button>
                </form>
            @elseif(!$todayAttendance->clock_out)
                <form action="{{ route('attendance.clock-out') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition-colors">
                        Clock Out
                    </button>
                </form>
            @else
                <div class="inline-flex items-center rounded-md bg-emerald-50 dark:bg-emerald-900/20 px-5 py-2 text-sm font-bold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 uppercase tracking-widest text-[10px]">
                    Shift Completed
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-emerald-50 dark:bg-emerald-900/20 p-4 border border-emerald-200 dark:border-emerald-800">
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Attendance History Table -->
    <div class="overflow-hidden bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Attendance Logs</h3>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-white dark:bg-slate-900">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Clock In</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Clock Out</th>
                        <th scope="col" class="px-3 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                        <th scope="col" class="px-3 py-3 text-right pr-6 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Duration</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($attendances as $attendance)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <td class="whitespace-nowrap py-4 px-6 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                            {{ $attendance->date->format('D, M d, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">
                            <div class="flex items-center">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2 shadow-sm"></span>
                                {{ $attendance->clock_in?->format('h:i A') ?? '--:--' }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">
                            <div class="flex items-center">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-300 dark:bg-slate-700 mr-2"></span>
                                {{ $attendance->clock_out?->format('h:i A') ?? '--:--' }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-center">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide border 
                                {{ $attendance->status === 'Present' ? 'border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'border-slate-200 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-400' }}">
                                {{ $attendance->status }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-slate-900 dark:text-white text-right pr-6">
                            @if($attendance->clock_in && $attendance->clock_out)
                                {{ number_format($attendance->clock_out->diffInHours($attendance->clock_in), 2) }}H
                            @else
                                <span class="text-slate-300 dark:text-slate-700">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if($attendances->isEmpty())
                    <tr><td colspan="5" class="py-12 text-center text-sm text-slate-400 italic">No attendance records found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50/50 dark:bg-slate-800/50 px-6 py-3 border-t border-slate-100 dark:border-slate-800">
            {{ $attendances->links() }}
        </div>
    </div>

    <!-- Correction Requests Table -->
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden mt-8">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Correction History</h3>
        </div>
        <div class="min-w-full align-middle text-left">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra">
                <thead>
                    <tr class="bg-white dark:bg-slate-900">
                        <th class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Requested Date</th>
                        <th class="py-3 px-4 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Adjustment</th>
                        <th class="py-3 px-4 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Reason</th>
                        <th class="py-3 px-6 text-right text-[10px] font-bold uppercase text-slate-500 tracking-wider pr-6">Processing Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($corrections as $correction)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <td class="py-4 px-6 text-sm font-bold text-slate-900 dark:text-white">{{ $correction->date->format('M d, Y') }}</td>
                        <td class="py-4 px-4 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-tight">
                            {{ $correction->requested_clock_in?->format('h:i A') ?? 'N/A' }} <span class="mx-1 text-slate-300">→</span> {{ $correction->requested_clock_out?->format('h:i A') ?? 'N/A' }}
                        </td>
                        <td class="py-4 px-4 text-xs text-slate-500 max-w-xs truncate italic">"{{ $correction->reason }}"</td>
                        <td class="py-4 px-6 text-right pr-6">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase border
                                {{ $correction->status === 'Approved' ? 'border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 
                                   ($correction->status === 'Rejected' ? 'border-red-200 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400' : 
                                   'border-brand-200 bg-brand-50 dark:bg-brand-950/30 text-brand-700 dark:text-brand-400') }}">
                                {{ $correction->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    @if($corrections->isEmpty())
                    <tr><td colspan="4" class="py-12 text-center text-sm text-slate-400 italic">No correction history found.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Correction Modal -->
<div id="correction-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Submit Correction</h3>
            <button onclick="document.getElementById('correction-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('attendance.request-correction') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label class="form-label">Shift Date</label>
                <input type="date" name="date" required class="form-input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Actual Clock In</label>
                    <input type="time" name="requested_clock_in" class="form-input">
                </div>
                <div>
                    <label class="form-label">Actual Clock Out</label>
                    <input type="time" name="requested_clock_out" class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Verification Reason</label>
                <textarea name="reason" rows="3" required class="form-input" placeholder="Explain the discrepancy (e.g., technical failure, offsite deployment)..."></textarea>
            </div>
            <button type="submit" class="w-full bg-brand-600 text-white py-2.5 rounded-md font-bold text-sm shadow-sm hover:bg-brand-700 transition-colors uppercase tracking-widest">Submit for Review</button>
        </form>
    </div>
</div>
@endsection
