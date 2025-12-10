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
        Schema::create('porchfests', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->string('subheading_primary')->nullable();
            $table->string('subheading_secondary')->nullable();

            $table->longText('description')->nullable();
            $table->string('image')->nullable();

            $table->integer('available_seats')->nullable();

            $table->json('categories')->nullable();
            $table->json('features')->nullable();

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
        Schema::dropIfExists('porchfests');
    }
};
