<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change course column from enum to string to support all course names
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `course` VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to enum with original values
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `course` ENUM('B.Tech CSE', 'B.Tech ECE', 'B.Tech EE', 'B.Tech ME') NULL");
    }
};
