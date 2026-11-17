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
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100)->index();
            $table->enum('body_type', [
                'suv',
                'mpv',
                'sedan',
                'hatchback',
                'city_car',
                'coupe',
                'wagon',
                'pickup',
                'commercial',
            ])->default('mpv');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
