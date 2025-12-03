<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('job_id');
            $table->foreign('job_id')->references('id')->on('job_listings')->onDelete('cascade');
            $table->text('cover_letter');
            $table->string('portfolio_link')->nullable();
            // $table->string('attachment');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdraw', 'interview', 'shortlisted', 'hired'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};