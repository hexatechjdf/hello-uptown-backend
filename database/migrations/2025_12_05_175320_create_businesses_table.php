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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // one business per user
            $table->string('business_name');
            $table->string('slug')->unique();

            // Descriptions
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->json('tags')->nullable();

            // Branding
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            // Contact & Location
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('redemption_radius')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('opening_hours')->nullable();

            // Social links
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();

            // Slider settings
            $table->string('slider_tagline')->nullable();
            $table->string('slider_section_text')->nullable();
            $table->string('slider_heading_one')->nullable();
            $table->string('slider_subheading')->nullable();
            $table->text('slider_short_description')->nullable();
            $table->string('slider_image')->nullable();
            $table->string('image_overlay_heading')->nullable();
            $table->string('image_overlay_heading2')->nullable();

            $table->string('slider_text1')->nullable();
            $table->string('slider_text1_value')->nullable();
            $table->string('slider_text2')->nullable();
            $table->string('slider_text2_value')->nullable();
            $table->string('slider_text3')->nullable();
            $table->string('slider_text3_value')->nullable();


            // Notification settings
            $table->boolean('send_new_deals')->default(true);

            // Status
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
