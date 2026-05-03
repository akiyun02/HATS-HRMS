@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Departments</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Configure and manage organizational units and departmental scope.</p>
        </div>
        <div class="mt-4 flex sm:ml-16 sm:mt-0 gap-3">
            <a href="{{ route('departments.create') }}" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                New Department
            </a>
        </div>
    </div>

    <!-- Grid of Departments -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($departments as $department)
        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden hover:border-brand-500 transition-colors duration-200 group">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-10 w-10 rounded-md bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75H21m-3 3.75H21m-15 3.75H21" />
                        </svg>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('job-roles.create', ['department_id' => $department->id]) }}" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 rounded-md transition-colors" title="Add Job Role">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        <a href="{{ route('departments.edit', $department) }}" class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-md transition-colors" title="Edit Department">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.125 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                            </svg>
                        </a>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $department->name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2 h-10">{{ $department->description ?? 'No description provided.' }}</p>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Team Size</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $department->job_roles_count }} Roles</span>
                    </div>
                    <a href="{{ route('job-roles.index', ['department' => $department->id]) }}" class="inline-flex items-center text-sm font-bold text-brand-600 hover:text-brand-700 transition-colors">
                        Explore Roles &rarr;
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        @if($departments->isEmpty())
        <div class="col-span-full py-12 text-center bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex flex-col items-center">
                <div class="h-12 w-12 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">No Departments Found</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Create your first organizational unit to get started.</p>
                <div class="mt-6">
                    <a href="{{ route('departments.create') }}" class="rounded-md bg-brand-600 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                        Create Department
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
