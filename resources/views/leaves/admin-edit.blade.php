@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <a href="{{ route('leaves.admin') }}" class="ml-2 text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors">Approvals</a>
    </li>
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Edit Request</span>
    </li>
@endsection

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Modify Leave Request</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Editing application for <span class="font-bold text-slate-700 dark:text-slate-200">{{ $leaveRequest->user->name }}</span></p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <form action="{{ route('leaves.admin.update', $leaveRequest) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="form-label">Leave Category</label>
                <select name="leave_type_id" required class="form-input">
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ $leaveRequest->leave_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('leave_type_id') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $leaveRequest->start_date->format('Y-m-d') }}" required class="form-input">
                    @error('start_date') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $leaveRequest->end_date->format('Y-m-d') }}" required class="form-input">
                    @error('end_date') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_half_day" id="is_half_day" value="1" {{ $leaveRequest->is_half_day ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 dark:bg-slate-800">
                <label for="is_half_day" class="text-sm font-semibold text-slate-700 dark:text-slate-300">This is a half-day leave request</label>
            </div>

            <div>
                <label class="form-label">Processing Status</label>
                <select name="status" required class="form-input">
                    <option value="Pending" {{ $leaveRequest->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ $leaveRequest->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $leaveRequest->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <label class="form-label">Employee's Reason</label>
                <textarea name="reason" rows="3" class="form-input">{{ $leaveRequest->reason }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('leaves.admin') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
                <button type="submit" class="rounded-md bg-brand-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
