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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Registration questions
            $table->date('arrival_date')->nullable(); // When are you coming for the event date
            $table->string('coming_from_city')->nullable(); // From which city you are coming
            $table->time('arrival_time')->nullable(); // Time of arrival (optional)
            $table->boolean('needs_stay')->default(false); // Do you need stay
            $table->boolean('coming_with_family')->default(false); // Are you coming with family
            $table->enum('travel_mode', ['car', 'train', 'flight', 'bus', 'other'])->nullable(); // How you are coming
            $table->text('return_journey_details')->nullable(); // Return Journey Details
            $table->text('memories_description')->nullable(); // Description of memories
            
            $table->timestamps();
            
            // Ensure one registration per user per event
            $table->unique(['event_id', 'user_id']);
        });

        // Pivot table for friends (many-to-many relationship)
        Schema::create('event_registration_friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained('event_registrations')->onDelete('cascade');
            $table->foreignId('friend_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['event_registration_id', 'friend_user_id'], 'event_reg_friends_unique');
        });

        // Table for memory photos
        Schema::create('event_registration_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained('event_registrations')->onDelete('cascade');
            $table->string('photo_path');
            $table->text('caption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registration_photos');
        Schema::dropIfExists('event_registration_friends');
        Schema::dropIfExists('event_registrations');
    }
};
