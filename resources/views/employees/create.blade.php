@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <a href="{{ route('employees.index') }}" class="ml-2 text-xs font-bold text-slate-500 hover:text-slate-700">Staff Directory</a>
    </li>
    <li class="flex items-center">
        <svg class="h-4 w-4 flex-shrink-0 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-2 text-xs font-bold text-slate-500">Onboard Employee</span>
    </li>
@endsection

@section('content')
    <div class="sm:flex sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800 pb-5 mb-6">
        <div class="min-w-0 flex-1">
            <h2 class="text-xl font-bold leading-tight text-slate-900 dark:text-white sm:text-2xl">Onboard New Employee</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Add a new staff member to the system.</p>
        </div>
    </div>

    <div class="" x-data="onboardingStepper()">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg">
                <div class="p-6">
                    
                    <!-- Stepper Header -->
                    <div class="mb-8">
                        <nav aria-label="Progress">
                            <ol role="list" class="flex items-center justify-between">
                                <template x-for="(step, index) in steps" :key="index">
                                    <li class="relative flex-1" :class="{'pr-8 sm:pr-20': index !== steps.length - 1}">
                                        <div class="absolute inset-0 flex items-center" aria-hidden="true" x-show="index !== steps.length - 1">
                                            <div class="h-0.5 w-full bg-slate-200 dark:bg-slate-700" :class="{'bg-brand-600 dark:bg-brand-500': currentStep > index + 1}"></div>
                                        </div>
                                        <a href="#" @click.prevent="currentStep = index + 1" class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 transition-colors" 
                                           :class="{'border-brand-600 bg-brand-600 text-white': currentStep >= index + 1, 'border-slate-300 bg-white dark:bg-slate-800 dark:border-slate-600 text-slate-500 dark:text-slate-400 hover:border-brand-600 hover:text-brand-600': currentStep < index + 1}">
                                            <span x-show="currentStep > index + 1">
                                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span x-show="currentStep <= index + 1" class="text-sm font-bold" x-text="index + 1"></span>
                                        </a>
                                        <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] uppercase font-bold tracking-wider w-max mt-2"
                                              :class="{'text-brand-600 dark:text-brand-400': currentStep === index + 1, 'text-slate-500 dark:text-slate-400': currentStep !== index + 1}"
                                              x-text="step.title"></span>
                                    </li>
                                </template>
                            </ol>
                        </nav>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul role="list" class="list-disc space-y-1 pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form id="onboardForm" action="{{ route('employees.store') }}" method="POST" class="mt-8">
                        @csrf

                        <!-- Step 1: Personal Info -->
                        <div x-show="currentStep === 1">
                            <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Full Name *</label>
                                    <input type="text" name="name" id="name" x-model="formData.name" required class="form-input mt-1">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email Address *</label>
                                    <input type="email" name="email" id="email" x-model="formData.email" required class="form-input mt-1">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone Number</label>
                                    <input type="text" name="phone" id="phone" x-model="formData.phone" class="form-input mt-1">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="gender" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gender</label>
                                    <select id="gender" name="gender" x-model="formData.gender" class="form-input mt-1">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="birthday" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Birthday</label>
                                    <input type="date" name="birthday" id="birthday" x-model="formData.birthday" class="form-input mt-1">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="marital_status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Marital Status</label>
                                    <select id="marital_status" name="marital_status" x-model="formData.marital_status" class="form-input mt-1">
                                        <option value="">Select Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-6">
                                    <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Address</label>
                                    <textarea id="address" name="address" rows="3" x-model="formData.address" class="form-input mt-1"></textarea>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="emergency_contact_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Emergency Contact Name</label>
                                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" x-model="formData.emergency_contact_name" class="form-input mt-1">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="emergency_contact_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Emergency Contact Phone</label>
                                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" x-model="formData.emergency_contact_phone" class="form-input mt-1">
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Employment Details -->
                        <div x-show="currentStep === 2" x-cloak>
                            <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white mb-4">Employment Details</h3>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="job_role_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Job Role *</label>
                                    <select id="job_role_id" name="job_role_id" x-model="formData.job_role_id" required class="form-input mt-1">
                                        <option value="">Select Job Role</option>
                                        @foreach($departments as $department)
                                            <optgroup label="{{ $department->name }}">
                                                @foreach($department->jobRoles as $role)
                                                    <option value="{{ $role->id }}" data-department="{{ $department->name }}" data-name="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="employee_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Employee ID</label>
                                    <input type="text" name="employee_id" id="employee_id" x-model="formData.employee_id" class="form-input mt-1">
                                    <p class="mt-1 text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">Leave blank to auto-generate or skip.</p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="joining_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Joining Date</label>
                                    <input type="date" name="joining_date" id="joining_date" x-model="formData.joining_date" class="form-input mt-1">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="probation_end_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Probation End Date</label>
                                    <input type="date" name="probation_end_date" id="probation_end_date" x-model="formData.probation_end_date" class="form-input mt-1">
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Compensation -->
                        <div x-show="currentStep === 3" x-cloak>
                            <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white mb-4">Compensation</h3>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="base_salary" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Base Salary (Annual/Monthly)</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" name="base_salary" id="base_salary" x-model="formData.base_salary" class="form-input pl-7 mt-1" placeholder="0.00" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Leave Policy Assignment -->
                        <div x-show="currentStep === 4" x-cloak>
                            <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white mb-4">Leave Policy Assignment</h3>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-6">
                                    <label for="leave_policy_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Assign Leave Policy</label>
                                    <select id="leave_policy_id" name="leave_policy_id" x-model="formData.leave_policy_id" class="form-input mt-1">
                                        <option value="">System Default</option>
                                        @foreach($leavePolicies as $policy)
                                            <option value="{{ $policy->id }}" data-name="{{ $policy->name }}">{{ $policy->name }} {{ $policy->is_default ? '(Default)' : '' }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">The selected policy will automatically generate prorated entitlements and initialize the leave ledger for this employee.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: System Access -->
                        <div x-show="currentStep === 5" x-cloak>
                            <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white mb-4">System Access</h3>
                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-6">
                                    <label for="role_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">System Role</label>
                                    <select id="role_id" name="role_id" x-model="formData.role_id" class="form-input mt-1">
                                        <option value="">Default (Employee)</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" data-name="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Initial Password</label>
                                    <input type="password" name="password" id="password" x-model="formData.password" class="form-input mt-1">
                                    <p class="mt-1 text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">Leave blank to auto-generate a secure password.</p>
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" x-model="formData.password_confirmation" class="form-input mt-1">
                                </div>
                                <div class="sm:col-span-6">
                                    <div class="flex items-start">
                                        <div class="flex h-5 items-center">
                                            <input id="send_invite_email" name="send_invite_email" type="checkbox" value="1" x-model="formData.send_invite_email" class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-brand-600 focus:ring-brand-500">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="send_invite_email" class="font-bold text-slate-700 dark:text-slate-200">Send Welcome Email</label>
                                            <p class="text-slate-500 dark:text-slate-400">Send an email invitation with login instructions to the employee's email address.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 6: Review -->
                        <div x-show="currentStep === 6" x-cloak>
                            <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white mb-4">Review & Submit</h3>
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-6 space-y-6 border border-slate-100 dark:border-slate-700">
                                <div>
                                    <h4 class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Personal Info</h4>
                                    <dl class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                        <div class="sm:col-span-1">
                                            <dt class="text-xs font-bold text-slate-500 dark:text-slate-400">Name</dt>
                                            <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white" x-text="formData.name || 'Not provided'"></dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="text-xs font-bold text-slate-500 dark:text-slate-400">Email</dt>
                                            <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white" x-text="formData.email || 'Not provided'"></dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                                    <h4 class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Employment</h4>
                                    <dl class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                        <div class="sm:col-span-1">
                                            <dt class="text-xs font-bold text-slate-500 dark:text-slate-400">Job Role</dt>
                                            <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white" x-text="getJobRoleName() || 'Not selected'"></dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="text-xs font-bold text-slate-500 dark:text-slate-400">Joining Date</dt>
                                            <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white" x-text="formData.joining_date || 'Not provided'"></dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                                    <h4 class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Settings</h4>
                                    <dl class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                        <div class="sm:col-span-1">
                                            <dt class="text-xs font-bold text-slate-500 dark:text-slate-400">Leave Policy</dt>
                                            <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white" x-text="getLeavePolicyName() || 'System Default'"></dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="text-xs font-bold text-slate-500 dark:text-slate-400">System Role</dt>
                                            <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-white" x-text="getRoleName() || 'Employee'"></dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 pt-5 border-t border-slate-200 dark:border-slate-800 flex justify-between">
                            <button type="button" x-show="currentStep > 1" @click="currentStep--" class="inline-flex items-center rounded-md bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                Previous
                            </button>
                            <div x-show="currentStep === 1"></div> <!-- Spacer if no prev button -->
                            
                            <button type="button" x-show="currentStep < steps.length" @click="nextStep" class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-brand-700 transition-colors">
                                Next Step
                                <svg class="ml-1.5 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                            
                            <button type="submit" x-show="currentStep === steps.length" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 transition-colors">
                                Complete Onboarding
                                <svg class="ml-1.5 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('onboardingStepper', () => ({
                currentStep: 1,
                steps: [
                    { title: 'Personal Info' },
                    { title: 'Employment' },
                    { title: 'Compensation' },
                    { title: 'Leave Policy' },
                    { title: 'System Access' },
                    { title: 'Review' }
                ],
                formData: {
                    name: '{{ old('name') }}',
                    email: '{{ old('email') }}',
                    phone: '{{ old('phone') }}',
                    gender: '{{ old('gender') }}',
                    birthday: '{{ old('birthday') }}',
                    marital_status: '{{ old('marital_status') }}',
                    address: '{{ old('address') }}',
                    emergency_contact_name: '{{ old('emergency_contact_name') }}',
                    emergency_contact_phone: '{{ old('emergency_contact_phone') }}',
                    job_role_id: '{{ old('job_role_id') }}',
                    employee_id: '{{ old('employee_id') }}',
                    joining_date: '{{ old('joining_date') }}',
                    probation_end_date: '{{ old('probation_end_date') }}',
                    base_salary: '{{ old('base_salary') }}',
                    leave_policy_id: '{{ old('leave_policy_id') }}',
                    role_id: '{{ old('role_id') }}',
                    password: '',
                    password_confirmation: '',
                    send_invite_email: {{ old('send_invite_email') ? 'true' : 'false' }}
                },
                nextStep() {
                    // Simple validation before proceeding
                    if (this.currentStep === 1) {
                        if (!this.formData.name || !this.formData.email) {
                            alert('Please fill out the required fields (Name, Email).');
                            return;
                        }
                    }
                    if (this.currentStep === 2) {
                        if (!this.formData.job_role_id) {
                            alert('Please select a Job Role.');
                            return;
                        }
                    }
                    if (this.currentStep === 5) {
                        if (this.formData.password && this.formData.password !== this.formData.password_confirmation) {
                            alert('Passwords do not match.');
                            return;
                        }
                    }
                    
                    if (this.currentStep < this.steps.length) {
                        this.currentStep++;
                    }
                },
                getJobRoleName() {
                    if (!this.formData.job_role_id) return '';
                    const select = document.getElementById('job_role_id');
                    if(select && select.options[select.selectedIndex]) {
                        return select.options[select.selectedIndex].getAttribute('data-name');
                    }
                    return '';
                },
                getLeavePolicyName() {
                    if (!this.formData.leave_policy_id) return '';
                    const select = document.getElementById('leave_policy_id');
                    if(select && select.options[select.selectedIndex]) {
                        return select.options[select.selectedIndex].getAttribute('data-name');
                    }
                    return '';
                },
                getRoleName() {
                    if (!this.formData.role_id) return '';
                    const select = document.getElementById('role_id');
                    if(select && select.options[select.selectedIndex]) {
                        return select.options[select.selectedIndex].getAttribute('data-name');
                    }
                    return '';
                }
            }))
        })
    </script>
@endsection
