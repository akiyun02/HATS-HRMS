@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">
                @if(isset($selectedDepartment))
                    {{ $selectedDepartment->name }} Positions
                @else
                    Job Roles & Specializations
                @endif
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 font-medium">Management of standard organizational positions and departmental alignment.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            @if(isset($selectedDepartment))
                <a href="{{ route('job-roles.index') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-bold text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50 transition-colors dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700">
                    Clear Filter
                </a>
            @endif
            <a href="{{ route('job-roles.create', ['department_id' => request('department') ?? request('department_id')]) }}" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create New Role
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Position Registry</h3>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-white dark:bg-slate-900">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-[10px] font-bold uppercase tracking-wider text-slate-500">Position Title</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Organizational Unit</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Description</th>
                        <th scope="col" class="relative py-3 pr-6 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($jobRoles as $role)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                        <td class="whitespace-nowrap py-4 px-6 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                            {{ $role->name }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold">
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide border border-brand-100 dark:border-brand-800 bg-brand-50 dark:bg-brand-950/30 text-brand-700 dark:text-brand-400">
                                {{ $role->department->name }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-xs text-slate-500 dark:text-slate-400 max-w-md truncate font-medium">
                            {{ $role->description ?? '—' }}
                        </td>
                        <td class="relative whitespace-nowrap py-4 pr-6 text-right">
                            <a href="{{ route('job-roles.edit', $role) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-tighter transition-colors">Edit Settings</a>
                        </td>
                    </tr>
                    @endforeach
                    @if($jobRoles->isEmpty())
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400 italic">No job roles defined.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
