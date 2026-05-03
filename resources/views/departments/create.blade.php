@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">New Organizational Unit</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Configure a new department for staff alignment and resource management.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Unit Configuration</h3>
        </div>
        <form action="{{ route('departments.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label for="name" class="form-label">Department Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input" placeholder="e.g. Strategic Operations">
                @error('name') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Departmental Scope</label>
                <textarea name="description" id="description" rows="4" class="form-input" placeholder="Define the primary focus and responsibilities of this unit...">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('departments.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
                <button type="submit" class="rounded-md bg-brand-600 px-8 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Confirm Unit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
