<?php

use App\Enums\OnboardingEnum;
use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('industry')->nullable();
            $table->text('company_size')->nullable()->default('1-10');
            $table->foreignUuid('state_id')->nullable()->references('id')->on('states')->nullOnDelete();
            $table->foreignUuid('country_id')->nullable()->references('id')->on('countries')->nullOnDelete();
            $table->string('website_url')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('official_email')->nullable();
            $table->string('onboarding_status')->default(OnboardingEnum::PENDING->value);
            $table->string('status')->default(Status::ACTIVE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('companies');
    }
};
