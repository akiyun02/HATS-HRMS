@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    @auth
        <div class="mb-8 flex justify-end">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg bg-white dark:bg-slate-800 px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Return to Dashboard
            </a>
        </div>
    @endauth

    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white sm:text-5xl tracking-tight">
            Join the <span class="text-brand-600">HATS</span> Team
        </h1>
        <p class="mt-4 text-xl text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
            Explore our current openings and find your next career move at HATS HRMS Portal.
        </p>
    </div>

    <div class="grid gap-8">
        @forelse($jobs as $job)
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group">
                <div class="p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2 py-0.5 rounded bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-[10px] font-bold uppercase tracking-widest border border-brand-100 dark:border-brand-500/20">
                                Open Position
                            </span>
                            <span class="text-xs font-medium text-slate-400">
                                Posted {{ $job->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white group-hover:text-brand-600 transition-colors">
                            {{ $job->title }}
                        </h2>
                        <div class="mt-4 text-slate-600 dark:text-slate-400 line-clamp-2 text-sm leading-relaxed">
                            {{ Str::limit(strip_tags($job->description), 200) }}
                        </div>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('careers.show', $job) }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 dark:bg-white px-6 py-3 text-sm font-bold text-white dark:text-slate-900 shadow-sm hover:bg-slate-800 dark:hover:bg-slate-100 transition-all hover:-translate-y-0.5">
                            View Details
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-4 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest">No Openings</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">We don't have any job openings at the moment. Please check back later!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-20 border-t border-slate-100 dark:border-slate-800 pt-12 flex flex-col sm:flex-row items-center justify-between gap-6 text-sm">
        <div class="text-slate-500 dark:text-slate-400 font-medium">
            Already an employee? 
            <a href="{{ route('login') }}" class="text-brand-600 font-bold hover:underline ml-1">Log in to your portal</a>
        </div>
        <div class="flex gap-4">
            <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors">LinkedIn</a>
            <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors">Twitter</a>
            <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors">Glassdoor</a>
        </div>
    </div>
</div>
@endsection
