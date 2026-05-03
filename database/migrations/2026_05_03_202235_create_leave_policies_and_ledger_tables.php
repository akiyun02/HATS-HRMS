<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_probationary')->default(false);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('leave_policy_leave_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_policy_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
            $table->decimal('annual_days', 5, 2);
            $table->string('accrual_type')->default('fixed'); // fixed, prorated, monthly
            $table->decimal('carry_over_limit', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('leave_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 5, 2); // can be negative for deductions
            $table->string('type'); // allocation, deduction, adjustment, carry_over
            $table->text('description')->nullable();
            $table->nullableMorphs('reference');
            $table->timestamps();
        });

        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->foreignId('leave_policy_id')->nullable()->constrained('leave_policies')->onDelete('set null');
            $table->date('probation_end_date')->nullable();
            $table->boolean('is_regularized')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropForeign(['leave_policy_id']);
            $table->dropColumn(['leave_policy_id', 'probation_end_date', 'is_regularized']);
        });
        Schema::dropIfExists('leave_ledger_entries');
        Schema::dropIfExists('leave_policy_leave_type');
        Schema::dropIfExists('leave_policies');
    }
};
