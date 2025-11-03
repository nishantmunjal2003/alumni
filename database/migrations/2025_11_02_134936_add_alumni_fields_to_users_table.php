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
            $table->string('phone')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('major')->nullable();
            $table->text('bio')->nullable();
            $table->string('current_position')->nullable();
            $table->string('company')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('profile_image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'graduation_year', 'major', 'bio',
                'current_position', 'company', 'linkedin_url',
                'profile_image', 'status'
            ]);
        });
    }
};
