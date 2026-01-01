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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('image')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->boolean('featured')->default(false);
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('article_url')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->enum('status', ['active', 'draft', 'expired', 'inactive'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
