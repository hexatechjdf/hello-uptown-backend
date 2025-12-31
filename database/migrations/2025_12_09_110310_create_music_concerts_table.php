<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('music_concerts', function (Blueprint $table) {
            $table->id();
            $table->string('main_heading');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('artist')->nullable();
            $table->longText('event_description');
            $table->string('image')->nullable();
            $table->integer('available_attendees')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('address')->nullable();
            $table->string('direction_link')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('website')->nullable();
            $table->string('ticket_link')->nullable();
            $table->json('time_json')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'active', 'expired'])->default('draft');
            $table->boolean('featured')->default(false);
            $table->date('event_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music_concerts');
    }
};
