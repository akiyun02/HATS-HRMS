@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-5xl">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Entry Detail #{{ $auditLog->id }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Generated on {{ $auditLog->created_at->format('F d, Y \a\t H:i:s') }}</p>
        </div>
        <div class="mt-4 flex sm:ml-16 sm:mt-0">
            <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center text-sm font-bold text-brand-600 hover:text-brand-700 transition-colors">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                Back to Registry
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Metadata -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Event Context</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-6">
                        <div>
                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Action Type</dt>
                            <dd class="text-sm font-bold text-slate-900 dark:text-white capitalize flex items-center">
                                <span class="h-2 w-2 rounded-full mr-2 {{ $auditLog->action === 'created' ? 'bg-emerald-500' : ($auditLog->action === 'updated' ? 'bg-brand-500' : 'bg-red-500') }}"></span>
                                {{ $auditLog->action }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Initiated By</dt>
                            <dd class="text-sm font-bold text-slate-900 dark:text-white">{{ $auditLog->user->name ?? 'System Process' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Target Object</dt>
                            <dd class="text-sm font-semibold text-slate-600 dark:text-slate-400 italic">{{ class_basename($auditLog->model_type) }} [ID: {{ $auditLog->model_id }}]</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Network Signature</dt>
                            <dd class="text-xs font-mono font-medium text-slate-500">{{ $auditLog->ip_address }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Changes -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Data Transformation</h3>
                </div>
                <div class="min-w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 table-zebra text-left">
                        <thead class="bg-white dark:bg-slate-900">
                            <tr>
                                <th class="py-3 px-6 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Attribute</th>
                                <th class="py-3 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Prior State</th>
                                <th class="py-3 pr-6 text-[10px] font-bold text-brand-600 uppercase tracking-wider">New State</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @php
                                $allKeys = array_unique(array_merge(
                                    array_keys($auditLog->old_values ?? []),
                                    array_keys($auditLog->new_values ?? [])
                                ));
                            @endphp

                            @foreach($allKeys as $key)
                            @if(!in_array($key, ['updated_at', 'created_at', 'id']))
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-4 px-6 text-xs font-bold text-slate-900 dark:text-white uppercase tracking-tight">{{ str_replace('_', ' ', $key) }}</td>
                                <td class="py-4 px-4 text-xs text-slate-500 font-medium italic">
                                    {{ is_array($val = ($auditLog->old_values[$key] ?? null)) ? 'JSON Object' : ($val ?? '—') }}
                                </td>
                                <td class="py-4 pr-6 text-xs text-brand-600 dark:text-brand-400 font-bold">
                                    {{ is_array($val = ($auditLog->new_values[$key] ?? null)) ? 'JSON Object' : ($val ?? '—') }}
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
