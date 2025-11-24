<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('application_id');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->uuid('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('users')->onDelete('cascade');
            $table->uuid('employer_id');
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
