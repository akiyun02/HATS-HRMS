@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">My Performance</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">My Performance</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Review historical feedback and performance metrics from management.</p>
        </div>
    </div>

    <!-- Review Feed -->
    <div class="space-y-6">
        @forelse($reviews as $review)
        <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden transition-all duration-200 hover:border-brand-500 group">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-xs shadow-sm">
                        {{ $review->review_date->format('d') }}
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight">{{ $review->review_date->format('F Y') }} Assessment</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reviewed by {{ $review->reviewer->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-800' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
            </div>
            <div class="p-6">
                <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-md border border-slate-100 dark:border-slate-800">
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic">"{{ $review->feedback }}"</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="h-12 w-12 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 flex items-center justify-center mx-auto mb-4">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01-.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">No Feedback Logged</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Your historical performance reviews will appear here once submitted by management.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
