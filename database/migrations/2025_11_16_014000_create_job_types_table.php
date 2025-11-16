<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['entry level', 'mid level', 'senior level', 'manager', 'executive']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_types');
    }
};
