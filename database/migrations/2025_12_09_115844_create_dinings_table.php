<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dinings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('direction_link')->nullable();
            $table->string('phone')->nullable();
            $table->json('cuisine_types')->nullable();
            $table->json('time')->nullable();

            $table->string('price_range')->nullable();
            $table->string('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            $table->enum('status', ['active', 'draft', 'expired', 'inactive'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinings');
    }
};
