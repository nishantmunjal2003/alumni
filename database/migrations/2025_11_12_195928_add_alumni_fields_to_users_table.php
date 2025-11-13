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
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'graduation_year')) {
                $table->string('graduation_year')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'major')) {
                $table->string('major')->nullable()->after('graduation_year');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('major');
            }
            if (!Schema::hasColumn('users', 'current_position')) {
                $table->string('current_position')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable()->after('current_position');
            }
            if (!Schema::hasColumn('users', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('company');
            }
            if (!Schema::hasColumn('users', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('linkedin_url');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('profile_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'graduation_year',
                'major',
                'bio',
                'current_position',
                'company',
                'linkedin_url',
                'profile_image',
                'status',
            ]);
        });
    }
};
