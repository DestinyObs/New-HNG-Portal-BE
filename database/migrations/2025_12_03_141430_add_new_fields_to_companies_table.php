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
        Schema::table('companies', function (Blueprint $table) {
            $table->text('tagline')->nullable()->after('description');
            $table->text('value_proposition')->nullable()->after('tagline');
            $table->text('why_talents_should_work_with_us')->nullable()->after('value_proposition');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('tagline');
            $table->dropColumn('value_proposition');
            $table->dropColumn('why_talents_should_work_with_us');
        });
    }
};
