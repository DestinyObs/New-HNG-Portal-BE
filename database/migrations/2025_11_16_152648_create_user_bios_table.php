<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_bios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('content');
            $table->decimal('min_salary', 15, 2)->nullable();
            $table->decimal('max_salary', 15, 2)->nullable();
            $table->uuid('track_id')->nullable();
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('set null');
            $table->boolean('is_verified')->default(false);
            $table->json('links')->nullable();
            $table->uuid('cv_id')->nullable();
            $table->foreign('cv_id')->references('id')->on('media_assets')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_bios');
    }
};
