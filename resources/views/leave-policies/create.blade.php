@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('leave-policies.index') }}" class="text-slate-400 hover:text-brand-600 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Create Leave Policy</h2>
        </div>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 ml-8">Set up a new policy with custom entitlement rules and regional compliance.</p>
    </div>

    <form action="{{ route('leave-policies.store') }}" method="POST" class="space-y-8">
        @csrf
        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="name" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-2">Policy Name</label>
                    <input type="text" name="name" id="name" required class="form-input w-full bg-slate-50/50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 focus:ring-brand-600 focus:border-brand-600 rounded-lg text-sm font-semibold" placeholder="e.g. Philippine Standard Policy">
                </div>
                <div class="flex items-end gap-8 pb-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_default" value="1" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-600 dark:bg-slate-800 transition-all">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-brand-600 transition-colors uppercase tracking-widest">Set as Default</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_probationary" value="1" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-600 dark:bg-slate-800 transition-all">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-brand-600 transition-colors uppercase tracking-widest">Probationary</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="description" class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-2">Policy Description</label>
                <textarea name="description" id="description" rows="3" class="form-input w-full bg-slate-50/50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700 focus:ring-brand-600 focus:border-brand-600 rounded-lg text-sm font-medium" placeholder="Describe the target employee group for this policy..."></textarea>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Entitlement Matrix</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Define days and accrual types</span>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                    <thead class="bg-slate-50/30 dark:bg-slate-800/30">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">Leave Type</th>
                            <th class="px-8 py-4 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest w-32">Annual Days</th>
                            <th class="px-8 py-4 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">Accrual Logic</th>
                            <th class="px-8 py-4 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest w-32">Carry Limit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @foreach($leaveTypes as $index => $type)
                        <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-8 py-5">
                                <input type="hidden" name="leave_types[{{ $index }}][id]" value="{{ $type->id }}">
                                <span class="text-sm font-bold text-slate-900 dark:text-white block">{{ $type->name }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 block truncate max-w-xs">{{ $type->description }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <input type="number" name="leave_types[{{ $index }}][annual_days]" step="0.5" min="0" value="0" class="form-input w-20 text-center text-sm font-bold bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-md focus:ring-brand-500">
                            </td>
                            <td class="px-8 py-5 text-center">
                                <select name="leave_types[{{ $index }}][accrual_type]" class="form-input text-xs font-bold bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-md focus:ring-brand-500 uppercase tracking-tight">
                                    <option value="fixed">Fixed (Lump Sum)</option>
                                    <option value="prorated">Prorated (By Hire Date)</option>
                                    <option value="monthly">Monthly (Accrual)</option>
                                </select>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <input type="number" name="leave_types[{{ $index }}][carry_over_limit]" step="0.5" min="0" value="0" class="form-input w-20 text-center text-sm font-bold bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-md focus:ring-brand-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end items-center gap-4 pt-4">
            <a href="{{ route('leave-policies.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 uppercase tracking-[0.2em] transition-colors">Cancel</a>
            <button type="submit" class="bg-brand-600 text-white px-10 py-3 rounded-lg text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-brand-600/20 hover:bg-brand-700 hover:-translate-y-0.5 transition-all active:translate-y-0">
                Register Policy
            </button>
        </div>
    </form>
</div>
@endsection
