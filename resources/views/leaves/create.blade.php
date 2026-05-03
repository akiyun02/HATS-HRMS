@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between border-b border-slate-100 dark:border-slate-800 pb-5">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Apply for Leave</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Submit your leave request for manager approval.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden">
        <form action="{{ route('leaves.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div>
                <label for="leave_type_id" class="form-label">Leave Category</label>
                <select id="leave_type_id" name="leave_type_id" required class="form-input">
                    @foreach($leaveTypes as $type)
                        @php
                            $balance = auth()->user()->leaveBalances()->where('leave_type_id', $type->id)->where('year', now()->year)->first();
                            $available = $balance ? $balance->available_days : 0;
                        @endphp
                        <option value="{{ $type->id }}">{{ $type->name }} (Available: {{ $available }} days)</option>
                    @endforeach
                </select>
                @error('leave_type_id') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="form-input">
                    @error('start_date') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="form-input">
                    @error('end_date') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_half_day" id="is_half_day" value="1" class="rounded border-slate-300 dark:border-slate-700 text-brand-600 focus:ring-brand-500 dark:bg-slate-800">
                <label for="is_half_day" class="text-sm font-semibold text-slate-700 dark:text-slate-300">This is a half-day leave request</label>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const startDateInput = document.getElementById('start_date');
                    const endDateInput = document.getElementById('end_date');
                    const halfDayCheckbox = document.getElementById('is_half_day');

                    startDateInput.addEventListener('change', function() {
                        if (this.value) {
                            endDateInput.min = this.value;
                            if (endDateInput.value && endDateInput.value < this.value) {
                                endDateInput.value = this.value;
                            }
                        }
                    });
                    
                    halfDayCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            if (startDateInput.value) {
                                endDateInput.value = startDateInput.value;
                            }
                            endDateInput.readOnly = true;
                            endDateInput.classList.add('bg-slate-50', 'dark:bg-slate-800', 'text-slate-500', 'cursor-not-allowed');
                        } else {
                            endDateInput.readOnly = false;
                            endDateInput.classList.remove('bg-slate-50', 'dark:bg-slate-800', 'text-slate-500', 'cursor-not-allowed');
                        }
                    });
                });
            </script>

            <div>
                <label for="reason" class="form-label">Reason for Absence</label>
                <textarea name="reason" id="reason" rows="3" class="form-input" placeholder="Please provide brief details..."></textarea>
                @error('reason') <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('leaves.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
                <button type="submit" class="rounded-md bg-brand-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

