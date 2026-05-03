<?php

namespace App\Services\Employee;

use App\Models\Role;
use App\Models\User;
use App\Models\LeavePolicy;
use App\Services\Leave\LeaveEntitlementService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OnboardEmployeeService
{
    public function __construct(
        public LeaveEntitlementService $leaveService
    ) {}

    /**
     * Handle the full employee onboarding process.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function onboard(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $plainPassword = $data['password'] ?? Str::random(12);
            $user = $this->createUser($data, $plainPassword);
            
            $this->assignRole($user, $data['role_id'] ?? null);
            
            $this->createEmployeeProfile($user, $data);
            
            $this->setupLeaveEntitlements($user, $data['leave_policy_id'] ?? null);
            
            // Payroll setup could be added here if needed (e.g. creating default payroll components)
            // $this->setupPayroll($user, $data);

            if ($data['send_invite_email'] ?? false) {
                $this->sendInviteEmail($user, $plainPassword);
            }

            return ['user' => $user, 'plain_password' => $plainPassword, 'was_auto_generated' => empty($data['password'])];
        });
    }

    private function createUser(array $data, string $plainPassword): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($plainPassword),
        ]);
    }

    private function assignRole(User $user, ?int $roleId): void
    {
        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $user->roles()->attach($role);
                return;
            }
        }

        // Fallback to Employee role
        $employeeRole = Role::where('name', 'Employee')->first();
        if ($employeeRole) {
            $user->roles()->attach($employeeRole);
        }
    }

    private function createEmployeeProfile(User $user, array $data): void
    {
        $user->employeeProfile()->create([
            'job_role_id' => $data['job_role_id'],
            'base_salary' => $data['base_salary'] ?? 0,
            'employee_id' => $data['employee_id'] ?? null,
            'joining_date' => $data['joining_date'] ?? null,
            'probation_end_date' => $data['probation_end_date'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'gender' => $data['gender'] ?? null,
            'birthday' => $data['birthday'] ?? null,
            'marital_status' => $data['marital_status'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'leave_policy_id' => $data['leave_policy_id'] ?? LeavePolicy::where('is_default', true)->first()?->id,
        ]);
    }

    private function setupLeaveEntitlements(User $user, ?int $policyId): void
    {
        $policy = LeavePolicy::find($policyId) 
                  ?? LeavePolicy::where('is_default', true)->first();

        if ($policy) {
            // Assign policy and initialize leave ledger via the LeaveEntitlementService
            $this->leaveService->assignPolicy($user, $policy);
        }
    }

    private function setupPayroll(User $user, array $data): void
    {
        // Placeholder for future payroll setup logic
        // E.g., setting up fixed allowances or deductions
    }

    private function sendInviteEmail(User $user, ?string $plainPassword): void
    {
        // Send email with credentials to the user
        // Mail::to($user->email)->send(new EmployeeWelcomeMail($user, $plainPassword));
    }
}
