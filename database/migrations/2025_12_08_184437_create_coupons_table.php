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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();

            $table->string('title');
            $table->string('coupon_code')->unique();
            $table->string('short_description')->nullable();
            $table->text('long_description')->nullable();

            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 10, 2);

            $table->unsignedBigInteger('category_id')->nullable()->index();

            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();

            $table->integer('usage_limit_per_user')->default(1);
            $table->decimal('minimum_spend', 10, 2)->nullable();

            $table->text('terms_conditions')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
