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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->string('title');
            $table->string('short_description')->nullable();
            $table->text('long_description')->nullable();

            $table->string('image')->nullable();

            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('original_price', 10, 2)->nullable();

            $table->unsignedBigInteger('category_id')->nullable();

            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();

            $table->text('terms_conditions')->nullable();
            $table->boolean('is_featured')->default(false);

            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
