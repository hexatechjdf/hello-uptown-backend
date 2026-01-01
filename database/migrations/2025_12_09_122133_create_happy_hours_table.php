<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('happy_hours', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->boolean('featured')->default(false);
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->json('open_hours')->nullable();
            $table->json('deals')->nullable();
            $table->text('special_offer')->nullable();
            $table->string('direction_link')->nullable();
            $table->enum('status', ['active', 'draft', 'expired', 'inactive'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('happy_hours');
    }
};
