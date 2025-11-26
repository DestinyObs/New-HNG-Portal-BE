<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookmarked_jobs', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('job_listing_id');
            $table->timestamps();

            $table->primary(['user_id', 'job_listing_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_listing_id')->references('id')->on('job_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmarked_jobs');
    }
};