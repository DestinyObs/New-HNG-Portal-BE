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
            $table->foreignId('candidate_location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 15, 2)->nullable();
            $table->foreignId('track_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('job_type_id')->nullable()->constrained('job_types')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
