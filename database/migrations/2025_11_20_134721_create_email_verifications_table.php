<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Proper UUID foreign key (no integer)
            $table->uuid('user_id');

            $table->string('token')->unique();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // Correct FK reference for UUID
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_verifications');
    }
};
