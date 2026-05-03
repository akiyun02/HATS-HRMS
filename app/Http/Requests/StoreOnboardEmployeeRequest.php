<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOnboardEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We'll rely on controller middleware/policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Info
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'string', Rule::in(['Male', 'Female', 'Other'])],
            'birthday' => ['nullable', 'date'],
            'marital_status' => ['nullable', 'string', Rule::in(['Single', 'Married', 'Divorced', 'Widowed'])],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],

            // Employment Details
            'job_role_id' => ['required', 'exists:job_roles,id'],
            'employee_id' => ['nullable', 'string', 'unique:employee_profiles,employee_id'],
            'joining_date' => ['nullable', 'date'],
            'probation_end_date' => ['nullable', 'date', 'after_or_equal:joining_date'],

            // Compensation
            'base_salary' => ['nullable', 'numeric', 'min:0'],

            // System Access & Options
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'send_invite_email' => ['boolean'],
            'leave_policy_id' => ['nullable', 'exists:leave_policies,id'],
        ];
    }
}
