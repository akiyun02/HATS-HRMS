@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Audit Logs</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 font-medium">Historical tracking of sensitive organizational data changes and administrative actions.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
        <form action="{{ route('audit-logs.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-12" placeholder="Search by user, action, or object...">
            </div>
            <button type="submit" class="bg-brand-600 text-white px-8 rounded-md font-bold text-xs uppercase tracking-wider hover:bg-brand-700 transition-colors shadow-sm">Search Logs</button>
            <a href="{{ route('audit-logs.index') }}" class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-8 rounded-md font-bold text-xs uppercase tracking-wider flex items-center justify-center hover:bg-slate-200 transition-colors border border-slate-200 dark:border-slate-700">Reset</a>
        </form>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Audit Registry</h3>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-white dark:bg-slate-900">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Timestamp</th>
                        <th scope="col" class="px-3 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">User</th>
                        <th scope="col" class="px-3 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Action</th>
                        <th scope="col" class="px-3 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Affected Object</th>
                        <th scope="col" class="px-3 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Network ID</th>
                        <th scope="col" class="relative py-4 pr-6 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                        <td class="whitespace-nowrap py-4 px-6 text-sm font-bold text-slate-900 dark:text-white">
                            {{ $log->created_at->format('M d, H:i') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-slate-600 dark:text-slate-400">
                            {{ $log->user->name ?? 'System' }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide border 
                                {{ $log->action === 'created' ? 'border-emerald-200 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 
                                   ($log->action === 'updated' ? 'border-brand-200 bg-brand-50 dark:bg-brand-950/30 text-brand-700 dark:text-brand-400' : 
                                   'border-red-200 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-bold text-slate-500 uppercase tracking-tight italic">
                            {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-slate-400 font-mono">
                            {{ $log->ip_address }}
                        </td>
                        <td class="relative whitespace-nowrap py-4 pr-6 text-right">
                            <a href="{{ route('audit-logs.show', $log) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-tighter">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50/50 dark:bg-slate-800/50 px-8 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
