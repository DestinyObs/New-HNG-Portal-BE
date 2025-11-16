<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->text('acceptance_criteria')->nullable();
            $table->uuid('candidate_location_id')->nullable();
            $table->foreign('candidate_location_id')->references('id')->on('locations')->onDelete('set null');
            $table->uuid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->decimal('price', 15, 2)->nullable();
            $table->uuid('track_id')->nullable();
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('set null');
            $table->uuid('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->uuid('job_type_id')->nullable();
            $table->foreign('job_type_id')->references('id')->on('job_types')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
