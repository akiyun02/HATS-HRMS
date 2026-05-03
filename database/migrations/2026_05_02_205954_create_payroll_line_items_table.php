<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_record_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['Addition', 'Deduction']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_line_items');
    }
};
