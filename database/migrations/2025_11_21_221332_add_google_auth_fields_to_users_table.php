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
        Schema::table('users', function (Blueprint $table) {
            // Google OAuth fields
            $table->string('google_id')->nullable()->unique();
            $table->text('google_access_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
            
            // Role field (if not already exists)
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['talent', 'employer', 'admin'])->default('talent');
            }
            
            // Name fields (if using split firstname/lastname)
            if (!Schema::hasColumn('users', 'firstname')) {
                $table->string('firstname')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'lastname')) {
                $table->string('lastname')->nullable();
            }
            
            // Indexes for performance
            $table->index(['google_id']);
            $table->index(['email']);
            $table->index(['role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove Google OAuth fields
            $table->dropColumn([
                'google_id',
                'google_access_token', 
                'google_refresh_token',
                'google_token_expires_at'
            ]);
            
            // Only drop role if we added it in this migration
            if (Schema::hasColumn('users', 'role') && !Schema::hasColumn('users', 'role_before_migration')) {
                $table->dropColumn('role');
            }
            
            // Drop indexes
            $table->dropIndex(['google_id']);
            $table->dropIndex(['email']); 
            $table->dropIndex(['role']);
        });
    }
};