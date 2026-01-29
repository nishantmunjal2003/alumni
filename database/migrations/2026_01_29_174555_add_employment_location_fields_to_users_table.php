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
            // Check if columns exist before adding them to avoid errors if re-running or partial migrations
            if (!Schema::hasColumn('users', 'employment_country')) {
                $table->string('employment_country')->nullable()->after('employment_address');
            }
            // employment_city and employment_state might already exist in validation but let's ensure they are in DB
             if (!Schema::hasColumn('users', 'employment_city')) {
                $table->string('employment_city')->nullable()->after('employment_address');
            }
             if (!Schema::hasColumn('users', 'employment_state')) {
                $table->string('employment_state')->nullable()->after('employment_city');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn(['employment_country', 'employment_city', 'employment_state']);
        });
    }
};
