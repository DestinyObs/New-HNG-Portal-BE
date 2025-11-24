<?php

use App\Enums\OnboardingEnum;
use App\Enums\Status;
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
            $table->text('content')->nullable();
            $table->decimal('min_salary', 15, 2)->nullable();
            $table->decimal('max_salary', 15, 2)->nullable();
            $table->uuid('track_id')->nullable();
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('set null');
            $table->boolean('is_verified')->default(false);
            $table->json('links')->nullable();
            $table->uuid('cv_id')->nullable();
            $table->foreign('cv_id')->references('id')->on('media_assets')->onDelete('set null');
            $table->string('current_role')->nullable();
            $table->text('bio')->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_url')->nullable();
            $table->foreignUuid('state_id')->nullable()->references('id')->on('states')->nullOnDelete();
            $table->foreignUuid('country_id')->nullable()->references('id')->on('countries')->nullOnDelete();
            $table->string('onboarding_status')->default(OnboardingEnum::PENDING->value);
            $table->string('status')->default(Status::ACTIVE->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_bios');
    }
};
