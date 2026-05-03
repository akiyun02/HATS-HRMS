@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Staff Directory</span>
    </li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
            <div class="min-w-0 flex-1">
                <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Staff Directory</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">View and manage all active employees and their organizational assignments.</p>
            </div>
            <div class="mt-4 flex sm:ml-16 sm:mt-0 gap-3">
                <a href="{{ route('employees.export') }}"
                    class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <svg class="-ml-0.5 mr-2 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Export Directory
                </a>
                <a href="{{ route('employees.create') }}"
                    class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Onboard Staff
                </a>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
            <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-10"
                            placeholder="Search by name, email, or position...">
                    </div>
                </div>
                <div>
                    <select name="department" class="form-input">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-brand-600 text-white rounded-md font-bold text-xs uppercase tracking-wider hover:bg-brand-700 transition-colors">Filter</button>
                    <a href="{{ route('employees.index') }}"
                        class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-md font-bold text-xs uppercase tracking-wider flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-700">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div
            class="overflow-hidden bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
            <div class="min-w-full align-middle">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra">
                    <thead class="bg-white dark:bg-slate-900 text-left">
                        <tr>
                            <th scope="col"
                                class="py-3.5 pl-6 pr-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Employee</th>
                            <th scope="col"
                                class="px-3 py-3.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Position & Unit</th>
                            <th scope="col"
                                class="px-3 py-3.5 text-right pr-6 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Registry Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                        @foreach($employees as $employee)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-150">
                                <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 shrink-0">
                                            <div
                                                class="h-10 w-10 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-sm">
                                                {{ substr($employee->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                {{ $employee->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                {{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <div class="text-slate-900 dark:text-white font-bold uppercase tracking-tight text-[11px]">
                                        {{ $employee->employeeProfile?->jobRole?->name ?? 'N/A' }}</div>
                                    <div
                                        class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        {{ $employee->employeeProfile?->jobRole?->department?->name ?? 'Unassigned Unit' }}</div>
                                </td>
                                <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('employees.show', $employee) }}"
                                            class="text-brand-600 dark:text-brand-400 hover:text-brand-700 font-bold text-xs uppercase tracking-tighter transition-colors">Manage Profile</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-slate-50/50 dark:bg-slate-800/50 px-6 py-3 border-t border-slate-100 dark:border-slate-800">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
@endsection
