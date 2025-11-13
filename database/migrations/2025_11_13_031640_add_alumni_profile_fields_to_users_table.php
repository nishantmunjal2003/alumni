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
            // Alumni Details
            $table->string('passing_year')->nullable()->after('graduation_year');
            $table->enum('course', ['B.Tech CSE', 'B.Tech ECE', 'B.Tech EE', 'B.Tech ME'])->nullable()->after('major');
            $table->string('proof_document')->nullable()->after('course'); // ID Card/Marksheet
            $table->text('residence_address')->nullable()->after('proof_document');
            $table->string('aadhar_number')->nullable()->after('residence_address');
            
            // Employment Details
            $table->string('designation')->nullable()->after('current_position');
            $table->enum('employment_type', ['Govt', 'Non-Govt'])->nullable()->after('designation');
            $table->text('employment_address')->nullable()->after('employment_type');
            $table->string('employment_city')->nullable()->after('employment_address');
            $table->string('employment_state')->nullable()->after('employment_city');
            $table->string('employment_pincode')->nullable()->after('employment_state');
            $table->string('alternate_email')->nullable()->after('employment_pincode');
            
            // Profile Status
            $table->boolean('profile_completed')->default(false)->after('status');
            $table->enum('profile_status', ['pending', 'approved', 'blocked'])->default('pending')->after('profile_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'passing_year',
                'course',
                'proof_document',
                'residence_address',
                'aadhar_number',
                'designation',
                'employment_type',
                'employment_address',
                'employment_city',
                'employment_state',
                'employment_pincode',
                'alternate_email',
                'profile_completed',
                'profile_status',
            ]);
        });
    }
};
