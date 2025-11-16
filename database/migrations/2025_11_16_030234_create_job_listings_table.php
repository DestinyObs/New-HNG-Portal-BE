<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('acceptance_criteria')->nullable();
            $table->foreignId('candidate_location_id')->constrained('locations')->onDelete('set null')->nullable();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2)->nullable();
            $table->foreignId('track_id')->constrained()->onDelete('set null')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('set null')->nullable();
            $table->foreignId('job_type_id')->constrained('job_types')->onDelete('set null')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
