<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_biometrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('rfid_uid')->nullable()->unique();
            $table->string('fingerprint_id')->nullable();
            // A user could have multiple fingerprints, but for simplicity, we map 1:1 or 1:N via this table
            // We'll add a unique constraint on (device_id or global fingerprint_id) if needed, but AS608 usually uses an int 1-127.
            $table->timestamps();

            // Assuming fingerprint_id is unique globally across all readers or synced
            $table->unique(['user_id', 'fingerprint_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_biometrics');
    }
};
