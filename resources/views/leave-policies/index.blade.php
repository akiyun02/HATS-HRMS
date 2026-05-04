@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Leave Policies</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Define and manage leave entitlements and accrual rules compliant with regional labor laws.</p>
        </div>
        <div class="mt-4 flex sm:ml-16 sm:mt-0 gap-3">
            <a href="{{ route('leave-policies.create') }}" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                New Policy
            </a>
        </div>
    </div>

    <!-- Grid of Policies -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
        @foreach($policies as $policy)
        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden hover:border-brand-500 transition-colors duration-200 flex flex-col h-full group">
            <div class="p-6 flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-10 w-10 rounded-md bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.966 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($policy->is_default)
                        <span class="inline-flex items-center rounded-full bg-brand-50 dark:bg-brand-900/20 px-2 py-0.5 text-[10px] font-black text-brand-700 dark:text-brand-400 border border-brand-100 dark:border-brand-800 uppercase tracking-widest">Default</span>
                        @endif
                        <div class="flex gap-1">
                            <a href="{{ route('leave-policies.edit', $policy) }}" class="p-1.5 text-slate-400 hover:text-brand-600 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-md transition-colors" title="Edit Policy">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.125 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                </svg>
                            </a>
                            @if(!$policy->is_default)
                            <form action="{{ route('leave-policies.destroy', $policy) }}" method="POST" onsubmit="return confirm('Are you sure? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-md transition-colors" title="Delete Policy">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $policy->name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2 h-10">{{ $policy->description ?? 'No description provided.' }}</p>
                
                <div class="space-y-3 pt-4 border-t border-slate-50 dark:border-slate-800">
                    <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Main Entitlements</h4>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($policy->leaveTypes->take(4) as $type)
                        <div class="bg-slate-50/50 dark:bg-slate-800/50 rounded p-2 border border-slate-100 dark:border-slate-800">
                            <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 truncate">{{ $type->name }}</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $type->pivot->annual_days }} Days</span>
                        </div>
                        @endforeach
                    </div>
                    @if($policy->leaveTypes->count() > 4)
                    <p class="text-[10px] font-bold text-slate-400 text-center uppercase tracking-widest">+ {{ $policy->leaveTypes->count() - 4 }} more leave types</p>
                    @endif
                </div>
            </div>
            
            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-50 dark:border-slate-800">
                <a href="{{ route('leave-policies.show', $policy) }}" class="flex items-center justify-center gap-2 text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-widest transition-colors">
                    View Full Configuration &rarr;
                </a>
            </div>
        </div>
        @endforeach

        @if($policies->isEmpty())
        <div class="col-span-full py-12 text-center bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex flex-col items-center">
                <div class="h-12 w-12 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.966 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">No Policies Found</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Create a policy compliant with labor laws to manage employee leaves.</p>
                <div class="mt-6">
                    <a href="{{ route('leave-policies.create') }}" class="rounded-md bg-brand-600 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                        Create Leave Policy
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
