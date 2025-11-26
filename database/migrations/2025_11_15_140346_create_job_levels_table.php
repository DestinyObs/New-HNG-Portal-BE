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
        Schema::create('job_levels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');  // e.g. "Senior-Level"
            $table->string('slug')->unique();  // e.g. "senior-level"
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_levels');
    }
};