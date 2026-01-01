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
        Schema::create('porchfests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();

            $table->string('slug')->unique()->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('direction_link')->nullable();
            $table->integer('attendees')->default(0);

            $table->integer('available_seats')->nullable();

            $table->json('genre')->nullable();
            $table->json('event_features')->nullable();
            $table->json('time')->nullable();

            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->enum('status', ['draft', 'scheduled', 'active', 'expired', 'inactive'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porchfests');
    }
};
