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
        Schema::table('payroll_records', function (Blueprint $table) {
            $table->decimal('gross_pay', 10, 2)->default(0)->after('year');
            $table->decimal('bonus', 10, 2)->default(0)->after('gross_pay');
            $table->decimal('deductions', 10, 2)->default(0)->after('bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_records', function (Blueprint $table) {
            $table->dropColumn(['gross_pay', 'bonus', 'deductions']);
        });
    }
};
