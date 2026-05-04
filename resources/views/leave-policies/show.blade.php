@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-6">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('leave-policies.index') }}" class="text-slate-400 hover:text-brand-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">{{ $leavePolicy->name }}</h2>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 ml-8">Detailed configuration and entitlement rules.</p>
        </div>
        <div class="flex items-center gap-3 ml-8 sm:ml-0">
            @if($leavePolicy->is_default)
            <span class="inline-flex items-center rounded-full bg-brand-50 dark:bg-brand-900/20 px-3 py-1 text-xs font-black text-brand-700 dark:text-brand-400 border border-brand-100 dark:border-brand-800 uppercase tracking-widest">Global Default</span>
            @endif
            <a href="{{ route('leave-policies.edit', $leavePolicy) }}" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Edit Configuration
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg p-6">
                <h3 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">Policy Overview</h3>
                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Status</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $leavePolicy->is_probationary ? 'Probationary' : 'Regular' }} Policy</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Created</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $leavePolicy->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-tight">Description</span>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $leavePolicy->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-brand-600 rounded-lg p-6 text-white shadow-sm">
                <div class="h-10 w-10 bg-white/20 rounded-md flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-2">Legal Compliance</h3>
                <p class="text-xs opacity-90 leading-relaxed">This policy is configured to meet regional labor standards. Ensure any modifications align with local labor laws to remain compliant.</p>
            </div>
        </div>

        <!-- Entitlements Table -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Leave Entitlements</h3>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                        <thead class="bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">Leave Type</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">Annual Days</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">Accrual Type</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">Carry Over</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($leavePolicy->leaveTypes as $type)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $type->name }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-xs">{{ $type->description }}</div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-400 border border-brand-100 dark:border-brand-800">
                                        {{ $type->pivot->annual_days }} Days
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-400 capitalize">
                                    {{ $type->pivot->accrual_type }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $type->pivot->carry_over_limit }} Days</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6 flex justify-between items-center text-xs text-slate-400 font-medium px-2">
                <p>System automatically calculates prorated days based on hire date where applicable.</p>
                <p>Version 2.4.0 (Compliance Checked)</p>
            </div>
        </div>
    </div>
</div>
@endsection
