@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Payroll</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Payroll</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage organizational salary disbursements and individual staff ledgers.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <a href="{{ route('payroll.export') }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <svg class="-ml-0.5 mr-2 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                Export History
            </a>
            <button onclick="document.getElementById('batch-modal').classList.remove('hidden')" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                Process Batch
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-emerald-50 dark:bg-emerald-900/20 p-4 border border-emerald-200 dark:border-emerald-800">
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Registry Table -->
    <div class="overflow-hidden bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Staff Ledgers</h3>
        </div>
        <div class="min-w-full align-middle">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                <thead class="bg-white dark:bg-slate-900">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-[10px] font-bold uppercase tracking-wider text-slate-500">Employee</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Last Period</th>
                        <th scope="col" class="px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-center">Base Salary</th>
                        <th scope="col" class="px-3 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 pr-6">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-150">
                        <td class="whitespace-nowrap py-4 px-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 shrink-0">
                                    <div class="h-10 w-10 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-sm">
                                        {{ substr($employee->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $employee->name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $employee->employeeProfile?->jobRole?->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-tight">
                            @if($lastRecord = $employee->payrollRecords->first())
                                {{ $lastRecord->month }} {{ $lastRecord->year }}
                            @else
                                <span class="text-slate-300 dark:text-slate-700">No Records</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-slate-900 dark:text-white text-center">
                            ₱{{ number_format($employee->employeeProfile?->base_salary ?? 0, 2) }}
                        </td>
                        <td class="relative whitespace-nowrap py-4 pr-6 text-right">
                            <a href="{{ route('payroll.show', $employee) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-tighter transition-colors">Manage Ledger</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Batch Modal -->
<div id="batch-modal" class="fixed inset-0 z-[110] hidden bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 transition-all" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-wider">Process Batch Payroll</h3>
            <button onclick="document.getElementById('batch-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('payroll.bulk-generate') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label class="form-label">Period Month</label>
                <select name="month" required class="form-input">
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                        <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Calendar Year</label>
                <input type="number" name="year" value="{{ date('Y') }}" required class="form-input">
            </div>
            <p class="text-xs text-slate-500 italic leading-relaxed">This will generate draft payroll records for all active employees. You can audit individual breakdowns before final disbursement.</p>
            <button type="submit" class="w-full bg-brand-600 text-white py-2.5 rounded-md font-bold text-sm shadow-sm hover:bg-brand-700 transition-colors uppercase tracking-widest">Execute Generation</button>
        </form>
    </div>
</div>
@endsection
