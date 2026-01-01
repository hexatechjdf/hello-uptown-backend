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
        Schema::create('nightlives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('venue_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->boolean('featured')->default(false);
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->json('tags')->nullable();
            $table->json('time')->nullable();
            $table->json('price')->nullable();
            $table->string('location')->nullable();
            $table->string('direction_link')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['active', 'draft', 'expired', 'inactive'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nightlives');
    }
};
