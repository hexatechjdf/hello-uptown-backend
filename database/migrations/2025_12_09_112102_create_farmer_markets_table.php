<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_markets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('heading');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('available_vendors')->nullable();
            $table->string('specialization')->nullable();
            $table->json('features')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('address')->nullable();
            $table->string('direction_link')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('website')->nullable();
            $table->string('ticket_link')->nullable();
            $table->json('schedule')->nullable();
            $table->date('next_market_date')->nullable();
            $table->boolean('featured')->default(false);
            $table->enum('status', ['draft', 'scheduled', 'active', 'expired'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_markets');
    }
};
