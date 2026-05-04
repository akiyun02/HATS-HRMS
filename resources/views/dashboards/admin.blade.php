@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Admin Dashboard</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Organizational Overview</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Welcome, {{ Auth::user()->name }}. Here's the current status of the workforce.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <a href="{{ route('employees.create') }}" class="inline-flex items-center rounded-md bg-brand-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                Onboard Employee
            </a>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                View Reports
            </a>
        </div>
    </div>

    <!-- Dashboard Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('tab-overview')" id="btn-tab-overview" class="tab-btn border-brand-500 text-brand-600 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold">
                Workforce
            </button>
            <button onclick="switchTab('tab-payroll')" id="btn-tab-payroll" class="tab-btn border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold">
                Payroll & Time
            </button>
            <button onclick="switchTab('tab-talent')" id="btn-tab-talent" class="tab-btn border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-semibold">
                Talent (ATS)
            </button>
        </nav>
    </div>

    <!-- Tab: Overview -->
    <div id="tab-overview" class="tab-content space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-slate-900 px-4 py-5 shadow-sm border border-slate-200 dark:border-slate-800 sm:px-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-brand-50 dark:bg-brand-900/20 p-3 rounded-md border border-brand-100 dark:border-brand-800">
                        <svg class="h-6 w-6 text-brand-600 dark:text-brand-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Workforce</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_employees'] }}</div>
                        </dd>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                    <a href="{{ route('employees.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-500">Directory &rarr;</a>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-slate-900 px-4 py-5 shadow-sm border border-slate-200 dark:border-slate-800 sm:px-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-md border border-emerald-100 dark:border-emerald-800">
                        <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Present Today</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['present_today'] }}</div>
                        </dd>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                    <a href="{{ route('attendance.admin') }}" class="text-xs font-bold text-brand-600 hover:text-brand-500">Log &rarr;</a>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-lg bg-white dark:bg-slate-900 px-4 py-5 shadow-sm border border-slate-200 dark:border-slate-800 sm:px-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-amber-50 dark:bg-amber-900/20 p-3 rounded-md border border-amber-100 dark:border-amber-800">
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Leave Applications</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['pending_leaves'] }}</div>
                        </dd>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-800">
                    <a href="{{ route('leaves.admin') }}" class="text-xs font-bold text-brand-600 hover:text-brand-500">Queue &rarr;</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Leave Approval Queue -->
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Leave Queue</h3>
                    <span class="inline-flex items-center rounded-full bg-amber-50 dark:bg-amber-900/20 px-2 py-0.5 text-[10px] font-bold text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-800">{{ $stats['pending_leaves'] }} PENDING</span>
                </div>
                <div class="flex-1 overflow-y-auto max-h-[350px]">
                    <ul role="list" class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($pendingApplications as $leave)
                        <li class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="flex items-center gap-x-4">
                                <div class="h-10 w-10 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-sm">
                                    {{ substr($leave->user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-auto">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $leave->user->name }}</p>
                                    <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $leave->leaveType->name }} • {{ $leave->start_date->format('M d') }}
                                    </p>
                                </div>
                                <a href="{{ route('leaves.admin') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 border border-transparent hover:border-brand-200 px-3 py-1 rounded-md transition-all">Review</a>
                            </div>
                        </li>
                        @empty
                        <li class="py-12 text-center text-sm text-slate-400 italic">No pending leave requests.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Decision History -->
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Recent Decisions</h3>
                </div>
                <div class="flex-1 overflow-y-auto max-h-[350px]">
                    <ul role="list" class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentDecisions as $leave)
                        <li class="p-4 opacity-80">
                            <div class="flex items-center gap-x-4">
                                <div class="min-w-0 flex-auto">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $leave->user->name }}</p>
                                    <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $leave->leaveType->name }} • {{ $leave->status }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase 
                                    {{ $leave->status === 'Approved' ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400' : 'text-red-700 bg-red-50 dark:text-red-400' }}">
                                    {{ $leave->status }}
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="py-12 text-center text-sm text-slate-400 italic">No recent history.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Payroll & Time -->
    <div id="tab-payroll" class="tab-content hidden space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg p-6">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-6">Attendance Operations</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-slate-50 dark:border-slate-800">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Monthly Punctuality</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">94.2%</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-50 dark:border-slate-800">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending Corrections</span>
                        <span class="text-sm font-bold text-amber-600">Active Queue</span>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <a href="{{ route('attendance.admin') }}" class="flex-1 text-center py-2 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded border border-slate-200 dark:border-slate-700 hover:bg-slate-100 transition-colors">Logs</a>
                        <a href="{{ route('attendance.export') }}" class="flex-1 text-center py-2 text-xs font-bold bg-brand-600 text-white rounded shadow-sm hover:bg-brand-700 transition-colors">Export</a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg p-6">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-6">Payroll Management</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-slate-50 dark:border-slate-800">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Active Period</span>
                        <span class="text-sm font-bold text-brand-600 uppercase tracking-tight">{{ now()->format('F Y') }}</span>
                    </div>
                    <div class="pt-4">
                        <form action="{{ route('payroll.bulk-generate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="month" value="{{ now()->format('F') }}">
                            <input type="hidden" name="year" value="{{ now()->format('Y') }}">
                            <button type="submit" class="w-full py-2 text-xs font-bold bg-brand-600 text-white rounded shadow-sm hover:bg-brand-700 transition-colors">Generate Batch Drafts</button>
                        </form>
                        <a href="{{ route('payroll.index') }}" class="block mt-2 text-center py-2 text-xs font-bold bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded border border-slate-200 dark:border-slate-700 hover:bg-slate-100 transition-colors">View Registry</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Talent -->
    <div id="tab-talent" class="tab-content hidden space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Active Openings</h3>
                    <a href="{{ route('recruitment.create') }}" class="text-[10px] font-bold text-brand-600 uppercase tracking-wider">+ New Posting</a>
                </div>
                <div class="p-0">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 table-zebra">
                        <thead class="bg-white dark:bg-slate-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase">Position</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase">Applicants</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($activeJobs as $job)
                            <tr>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $job->title }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-brand-600 text-center">{{ $job->applicants_count }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('recruitment.index') }}" class="text-xs font-bold text-slate-400 hover:text-brand-600 transition-colors">Pipeline</a>
                                </td>
                            </tr>
                            @endforeach
                            @if($activeJobs->isEmpty())
                            <tr><td colspan="3" class="py-12 text-center text-sm text-slate-400 italic">No active postings.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-brand-600 rounded-lg p-6 text-white shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold opacity-80 uppercase tracking-wider mb-6">Recruitment Engine</h3>
                    <p class="text-sm font-medium leading-relaxed opacity-90">Manage the hiring lifecycle from sourcing to final offer with the integrated ATS module.</p>
                </div>
                <div class="mt-8">
                    <a href="{{ route('recruitment.index') }}" class="block w-full text-center py-2.5 rounded bg-white text-brand-600 text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm">Launch Pipeline Console</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrative Quick Links -->
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg p-6">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-6">Management Control Center</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('departments.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="bg-slate-100 dark:bg-slate-800 p-1.5 rounded">
                    <svg class="h-4 w-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75H21m-3 3.75H21m-15 3.75H21" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Departments</span>
            </a>
            <a href="{{ route('audit-logs.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="bg-slate-100 dark:bg-slate-800 p-1.5 rounded">
                    <svg class="h-4 w-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Audit Logs</span>
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="bg-slate-100 dark:bg-slate-800 p-1.5 rounded">
                    <svg class="h-4 w-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.57 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" stroke-width="1.5"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Settings</span>
            </a>
            <a href="{{ route('payroll.index') }}" class="flex items-center gap-3 p-3 rounded-md border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="bg-slate-100 dark:bg-slate-800 p-1.5 rounded">
                    <svg class="h-4 w-4 text-slate-600 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Payroll</span>
            </a>
        </div>
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show target content
        document.getElementById(tabId).classList.remove('hidden');

        // Reset all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-brand-500', 'text-brand-600', 'font-bold');
            btn.classList.add('border-transparent', 'text-slate-500', 'font-semibold');
        });
        // Highlight active button
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.add('border-brand-500', 'text-brand-600', 'font-bold');
        activeBtn.classList.remove('border-transparent', 'text-slate-500', 'font-semibold');
    }
</script>
@endsection
