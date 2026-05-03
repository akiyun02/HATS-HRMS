@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">New Career Opportunity</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Publish a new job opening to the organizational recruitment portal.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Opening Configuration</h3>
        </div>
        <form action="{{ route('recruitment.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label for="title" class="form-label">Position Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="form-input" placeholder="e.g. Senior Human Resources Specialist">
                @error('title') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Job Description & Requirements</label>
                <textarea name="description" id="description" rows="6" class="form-input" placeholder="Define the core responsibilities, required skills, and cultural fit...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('recruitment.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                    Discard Draft
                </a>
                <button type="submit" class="rounded-md bg-brand-600 px-8 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Publish Opening
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
