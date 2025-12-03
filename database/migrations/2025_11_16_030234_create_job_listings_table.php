<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->text('acceptance_criteria')->nullable();

            // $table->uuid('state_id')->nullable();
            // $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');

            // $table->uuid('country_id')->nullable();
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');

            $table->string('state')->nullable();
            $table->string('country')->nullable();

            $table->uuid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->decimal('price', 15, 2)->nullable();
            $table->uuid('track_id')->nullable();
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('set null');
            $table->uuid('work_mode_id')->nullable();
            $table->foreign('work_mode_id')->references('id')->on('work_modes')->onDelete('set null');
            $table->uuid('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->uuid('job_type_id')->nullable();
            $table->foreign('job_type_id')->references('id')->on('job_types')->onDelete('set null');
            $table->uuid('job_level_id')->nullable();
            $table->foreign('job_level_id')->references('id')->on('job_levels')->onDelete('set null');
            $table->enum('publication_status', ['published', 'unpublished'])->default('unpublished');
            $table->enum('status', ['active', 'in-active', 'draft'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};