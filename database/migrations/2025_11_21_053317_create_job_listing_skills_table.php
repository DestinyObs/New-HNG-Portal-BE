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
        Schema::create('job_listing_skill', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->uuid('job_listing_id'); // match job_listings.id type
            $table->uuid('job_skill_id');   // match skills.id type

            $table->foreign('job_listing_id')
                ->references('id')->on('job_listings')
                ->onDelete('cascade');

            $table->foreign('job_skill_id')
                ->references('id')->on('skills')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listing_skill');
    }
};