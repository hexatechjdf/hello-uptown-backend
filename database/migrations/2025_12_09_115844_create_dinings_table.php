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
        Schema::create('dinings', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            // Arrays
            $table->json('tags')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            // Date & time
            $table->date('date')->nullable();
            $table->string('day', 20)->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status', ['active', 'draft', 'expired'])->default('draft');
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
