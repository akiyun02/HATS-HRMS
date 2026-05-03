@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-black leading-normal text-slate-900 dark:text-white sm:text-3xl sm:tracking-tight">Edit Employee Profile</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider text-[11px]">Update account and professional details for {{ $employee->name }}.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow-sm ring-1 ring-slate-200 dark:ring-slate-800 sm:rounded-3xl overflow-hidden">
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="divide-y divide-slate-100 dark:divide-slate-800">
            @csrf
            @method('PUT')
            
            <!-- Account Section -->
            <div class="p-8 sm:p-10">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-8 flex items-center tracking-tight">
                    <span class="bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 p-2 rounded-lg mr-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </span>
                    Account Information
                </h3>
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" required class="form-input">
                        @error('name') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required class="form-input">
                        @error('email') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Professional Section -->
            <div class="p-8 sm:p-10">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-8 flex items-center tracking-tight">
                    <span class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 p-2 rounded-lg mr-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </span>
                    Professional Status
                </h3>
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="department_id" class="form-label">Department</label>
                        <select id="department_id" name="department_id" class="form-input" onchange="updateJobRoles()">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $employee->employeeProfile?->jobRole?->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="job_role_id" class="form-label">Job Role</label>
                        <select id="job_role_id" name="job_role_id" required class="form-input">
                            <option value="">Select Job Role</option>
                            {{-- Will be populated by JS --}}
                        </select>
                        @error('job_role_id') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>

                    <script>
                        const departments = @json($departments);
                        const jobRoleSelect = document.getElementById('job_role_id');
                        const deptSelect = document.getElementById('department_id');
                        const initialJobRoleId = "{{ old('job_role_id', $employee->employeeProfile?->job_role_id) }}";

                        function updateJobRoles() {
                            const deptId = deptSelect.value;
                            jobRoleSelect.innerHTML = '<option value="">Select Job Role</option>';
                            
                            if (deptId) {
                                const dept = departments.find(d => d.id == deptId);
                                if (dept && dept.job_roles) {
                                    dept.job_roles.forEach(role => {
                                        const option = document.createElement('option');
                                        option.value = role.id;
                                        option.textContent = role.name;
                                        if (role.id == initialJobRoleId) {
                                            option.selected = true;
                                        }
                                        jobRoleSelect.appendChild(option);
                                    });
                                }
                            }
                        }

                        // Initialize on load
                        if (deptSelect.value) {
                            updateJobRoles();
                        }
                    </script>

                    <div>
                        <label for="employee_id" class="form-label">Employee ID</label>
                        <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $employee->employeeProfile?->employee_id) }}" class="form-input">
                        @error('employee_id') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="base_salary" class="form-label">Base Monthly Salary (₱)</label>
                        <input type="number" name="base_salary" id="base_salary" value="{{ old('base_salary', $employee->employeeProfile?->base_salary) }}" step="0.01" class="form-input">
                        @error('base_salary') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="joining_date" class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" id="joining_date" value="{{ old('joining_date', $employee->employeeProfile?->joining_date?->format('Y-m-d')) }}" class="form-input">
                        @error('joining_date') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="p-8 sm:p-10">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-8 flex items-center tracking-tight">
                    <span class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 p-2 rounded-lg mr-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    </span>
                    Communication
                </h3>
                <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div>
                        <label for="phone" class="form-label">Mobile Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->employeeProfile?->phone) }}" class="form-input">
                        @error('phone') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="address" class="form-label">Residential Address</label>
                        <textarea name="address" id="address" rows="3" class="form-input">{{ old('address', $employee->employeeProfile?->address) }}</textarea>
                        @error('address') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-tight">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-8 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between">
                <a href="{{ route('employees.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                    Back to directory
                </a>
                <button type="submit" class="rounded-2xl bg-brand-500 px-8 py-3.5 text-sm font-black text-white shadow-xl shadow-brand-500/20 hover:bg-brand-600 transition-all hover:scale-[1.02] active:scale-95 uppercase tracking-widest">
                    Save Updates
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white dark:bg-[#1a1a24] shadow-sm ring-1 ring-red-100 dark:ring-red-900/30 sm:rounded-3xl overflow-hidden mt-12 border-l-4 border-red-500">
        <div class="p-8 flex items-center justify-between">
            <div class="max-w-md">
                <h3 class="text-base font-black text-red-600 dark:text-red-500 uppercase tracking-widest">Terminate Record</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-tight mt-1">This will erase all historical data. This action cannot be reversed.</p>
            </div>
            <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure?')" class="rounded-xl bg-red-50 dark:bg-red-500/10 px-6 py-2.5 text-xs font-black text-red-600 dark:text-red-400 ring-1 ring-inset ring-red-200 dark:ring-red-500/20 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">
                    Delete Record
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
