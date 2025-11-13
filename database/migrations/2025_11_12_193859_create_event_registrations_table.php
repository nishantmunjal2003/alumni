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
            $table->date('arrival_date')->nullable();
            $table->string('coming_from_city')->nullable();
            $table->time('arrival_time')->nullable();
            $table->boolean('needs_stay')->default(false);
            $table->boolean('coming_with_family')->default(false);
            $table->enum('travel_mode', ['car', 'train', 'flight', 'bus', 'other'])->nullable();
            $table->text('return_journey_details')->nullable();
            $table->text('memories_description')->nullable();
            $table->timestamps();
            
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
