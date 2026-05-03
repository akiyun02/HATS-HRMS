@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Attendance Monitoring</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">System-wide monitoring of employee clock-in and clock-out activity.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <a href="{{ route('attendance.export') }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <svg class="-ml-0.5 mr-2 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                Export Registry
            </a>
        </div>
    </div>

    <!-- Pending Corrections -->
    @if(!$corrections->isEmpty())
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Adjustment Queue</h3>
            <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-900/20 px-2.5 py-0.5 text-[10px] font-bold text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-800">{{ $corrections->count() }} PENDING</span>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra">
                <thead class="bg-white dark:bg-slate-900 text-left">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Employee</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Date</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Requested Correction</th>
                        <th scope="col" class="px-3 py-3 text-right text-[10px] font-bold uppercase text-slate-500 tracking-wider pr-6">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($corrections as $correction)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-4 px-6 text-sm font-bold text-slate-900 dark:text-white">{{ $correction->user->name }}</td>
                        <td class="py-4 px-4 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-tight">{{ $correction->date->format('M d, Y') }}</td>
                        <td class="py-4 px-4 text-xs font-medium text-slate-500">
                            {{ $correction->requested_clock_in?->format('h:i A') ?? '--' }} <span class="mx-1">→</span> {{ $correction->requested_clock_out?->format('h:i A') ?? '--' }}
                        </td>
                        <td class="py-4 pr-6 text-right">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('attendance.correction.approve', $correction) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 uppercase tracking-widest px-3 py-1.5 rounded-md border border-emerald-100 dark:border-emerald-900/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all">Approve</button>
                                </form>
                                <form action="{{ route('attendance.correction.reject', $correction) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-red-600 hover:text-red-700 uppercase tracking-widest px-3 py-1.5 rounded-md border border-red-100 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
        <form action="{{ route('attendance.admin') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-10" placeholder="Search employee name...">
                </div>
            </div>
            <div>
                <input type="date" name="date" value="{{ request('date') }}" class="form-input">
            </div>
            <div>
                <select name="status" class="form-input">
                    <option value="">All Statuses</option>
                    <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>
            <button type="submit" class="bg-brand-600 text-white rounded-md font-bold text-xs uppercase tracking-wider hover:bg-brand-700 transition-colors shadow-sm">Filter Results</button>
        </form>
    </div>

    <!-- Main Registry Table -->
    <div class="overflow-hidden bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Clock Registry</h3>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra">
                <thead class="bg-white dark:bg-slate-900 text-left">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-[10px] font-bold uppercase text-slate-500 tracking-wider">Employee</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase text-slate-500 tracking-wider text-center">Date</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase text-slate-500 tracking-wider text-center">Clock In</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase text-slate-500 tracking-wider text-center">Clock Out</th>
                        <th scope="col" class="px-3 py-3 text-right text-[10px] font-bold uppercase text-slate-500 tracking-wider pr-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($attendances as $attendance)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                        <td class="whitespace-nowrap py-4 px-6 text-sm font-bold text-slate-900 dark:text-white">{{ $attendance->user->name }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400 text-center uppercase tracking-tight">{{ $attendance->date->format('M d, Y') }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-slate-500 text-center uppercase">{{ $attendance->clock_in?->format('h:i A') ?? '--:--' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-slate-500 text-center uppercase">{{ $attendance->clock_out?->format('h:i A') ?? '--:--' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-right pr-6">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide border 
                                {{ $attendance->status === 'Present' ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 
                                   ($attendance->status === 'Late' ? 'border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400' : 
                                   'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400') }}">
                                {{ $attendance->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50/50 dark:bg-slate-800/50 px-8 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
