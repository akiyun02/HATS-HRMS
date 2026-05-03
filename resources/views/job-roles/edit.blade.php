@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Modify Staff Position</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Updating settings for the <span class="font-bold text-slate-700 dark:text-slate-200">{{ $jobRole->name }}</span> position.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Position Settings</h3>
        </div>
        <form action="{{ route('job-roles.update', $jobRole) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="form-label">Role Title</label>
                <input type="text" name="name" id="name" value="{{ old('name', $jobRole->name) }}" required class="form-input">
                @error('name') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="department_id" class="form-label">Organizational Unit</label>
                <select name="department_id" id="department_id" required class="form-input">
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $jobRole->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Responsibilities & Scope</label>
                <textarea name="description" id="description" rows="4" class="form-input">{{ old('description', $jobRole->description) }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <a href="{{ route('job-roles.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                    Discard Changes
                </a>
                <div class="flex gap-3">
                    <form action="{{ route('job-roles.destroy', $jobRole) }}" method="POST" onsubmit="return confirm('Deleting a position will affect all assigned staff. Continue?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="rounded-md bg-white dark:bg-slate-950 px-4 py-2.5 text-xs font-bold text-red-600 border border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">Retire Position</button>
                    </form>
                    <button type="submit" class="rounded-md bg-brand-600 px-8 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                        Commit Updates
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
