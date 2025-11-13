<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop latitude and longitude columns
            $table->dropColumn(['latitude', 'longitude']);
        });

        // Rename event_date to event_start_date using raw SQL (works without Doctrine DBAL)
        DB::statement('ALTER TABLE events CHANGE event_date event_start_date DATETIME NOT NULL');

        Schema::table('events', function (Blueprint $table) {
            // Add event_end_date column (nullable for single day events)
            $table->dateTime('event_end_date')->nullable()->after('event_start_date');
        });

        Schema::table('events', function (Blueprint $table) {
            // Drop location column
            $table->dropColumn('location');
        });

        Schema::table('events', function (Blueprint $table) {
            // Add google_maps_link
            $table->string('google_maps_link', 500)->after('venue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('google_maps_link');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('location')->after('venue');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('event_end_date');
        });

        // Rename event_start_date back to event_date using raw SQL
        DB::statement('ALTER TABLE events CHANGE event_start_date event_date DATETIME NOT NULL');

        Schema::table('events', function (Blueprint $table) {
            $table->decimal('latitude', 8, 6)->nullable()->after('location');
            $table->decimal('longitude', 9, 6)->nullable()->after('latitude');
        });
    }
};
