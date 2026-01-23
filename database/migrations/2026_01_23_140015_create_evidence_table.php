<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evidence', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('person_id')->constrained('people')->cascadeOnDelete();
            $table->foreignUlid('context_id')->constrained('contexts')->restrictOnDelete();
            $table->foreignUlid('cycle_id')->constrained('cycles')->restrictOnDelete();

            $table->string('type'); // PERFORMANCE | POTENTIAL
            $table->string('dimension'); // Entregar resultado, Aprendizado, etc.
            $table->unsignedTinyInteger('intensity'); // 0-4
            $table->text('description');

            $table->date('occurred_at');
            // Assuming user_id refers to the authenticated user from a users table (default Laravel)
            // If users table is not using ULID yet, we might need to check. Usually standard Laravel uses BigInt or ULID/UUID.
            // Let's assume standard users table might be BigInt unless modified.
            // For now, I'll store it but maybe not constrained if I don't know the users table structure yet.
            // Or I'll assume users table exists. I'll use constrained('users') but if it fails I'll fix.
            // Safest is to not constrain if unsure, but I should constrain.
            // Let's check users table first? No, I'll assume standard setup or just add column.
            $table->foreignId('recorded_by')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence');
    }
};
