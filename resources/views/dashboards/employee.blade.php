@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">My Workspace</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Personal Workspace</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Welcome back, {{ Auth::user()->name }}. Here's your recent activity and schedule.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
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
            @endif
            <a href="{{ route('leaves.create') }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Request Leave
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Attendance -->
        <div class="relative overflow-hidden rounded-lg bg-white dark:bg-slate-900 px-4 py-5 shadow-sm border border-slate-200 dark:border-slate-800 sm:px-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-md border border-emerald-100 dark:border-emerald-800">
                    <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Days Present</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['attendance_count'] }}</div>
                        <span class="ml-2 text-xs text-slate-500 dark:text-slate-400 font-medium">this month</span>
                    </dd>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400">View Full Log &rarr;</a>
            </div>
        </div>

        <!-- Leaves -->
        <div class="relative overflow-hidden rounded-lg bg-white dark:bg-slate-900 px-4 py-5 shadow-sm border border-slate-200 dark:border-slate-800 sm:px-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-brand-50 dark:bg-brand-900/20 p-3 rounded-md border border-brand-100 dark:border-brand-800">
                    <svg class="h-6 w-6 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Leaves Taken</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['leaves_taken'] }}</div>
                        <span class="ml-2 text-xs text-slate-500 dark:text-slate-400 font-medium">approved</span>
                    </dd>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                <a href="{{ route('leaves.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400">My Requests &rarr;</a>
            </div>
        </div>

        <!-- Rating -->
        <div class="relative overflow-hidden rounded-lg bg-white dark:bg-slate-900 px-4 py-5 shadow-sm border border-slate-200 dark:border-slate-800 sm:px-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-50 dark:bg-amber-900/20 p-3 rounded-md border border-amber-100 dark:border-amber-800">
                    <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01-.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dt class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Latest Rating</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['latest_rating'] }}</div>
                        <span class="ml-1 text-xs text-slate-400 dark:text-slate-500 font-medium">/ 5.0</span>
                    </dd>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                <a href="{{ route('performance.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-500 dark:text-brand-400">Review Feedback &rarr;</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Upcoming Leaves -->
        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Upcoming Time Off</h3>
                <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-2 py-0.5 text-[10px] font-bold text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">SCHEDULE</span>
            </div>
            <div class="flex-1">
                <ul role="list" class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($upcomingLeaves as $leave)
                    <li class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-x-4">
                            <div class="min-w-0 flex-auto">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $leave->leaveType->name }}</p>
                                <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide
                                {{ $leave->status === 'Approved' ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400' : ($leave->status === 'Rejected' ? 'text-red-700 bg-red-50 dark:text-red-400' : 'text-brand-700 bg-brand-50 dark:text-brand-400') }}">
                                {{ $leave->status }}
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="p-12 text-center text-sm text-slate-400 italic">No upcoming leaves scheduled.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Profile Quick Info -->
        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Employment Details</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-800">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Position</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->employeeProfile?->jobRole?->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-800">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Department</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->employeeProfile?->jobRole?->department?->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-800">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">Employee ID</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ Auth::user()->employeeProfile?->employee_id ?? 'N/A' }}</span>
                </div>
                
                <div class="pt-4">
                    <a href="{{ route('profile.show') }}" class="block w-full text-center rounded-md bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 py-2 text-sm font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        View Full Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
