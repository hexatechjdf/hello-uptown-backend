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
        Schema::create('music_concerts', function (Blueprint $table) {
            $table->id();
            $table->string('main_heading');
            $table->string('sub_heading')->nullable();
            $table->longText('event_description');

            $table->string('image')->nullable();

            $table->integer('available_attendees')->nullable();

            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('place_id')->nullable();
            $table->string('website')->nullable();

            $table->enum('status', ['draft', 'scheduled', 'active', 'expired'])->default('draft');
            $table->date('event_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_concerts');
    }
};
