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
        Schema::create('art_fairs', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->string('slug')->unique()->nullable();
            $table->boolean('featured')->default(false);
            $table->string('direction_link')->nullable();
            $table->integer('available_artist')->default(0);
            $table->integer('artist_count')->default(0);

            $table->json('art_categories')->nullable();
            $table->json('event_features')->nullable();

            $table->enum('admission_type', ['free', 'paid'])->default('free');
            $table->decimal('admission_amount', 10, 2)->nullable();

            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->date('event_date');
            $table->string('day')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('status', ['draft', 'scheduled', 'active', 'expired'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('art_fairs');
    }
};
