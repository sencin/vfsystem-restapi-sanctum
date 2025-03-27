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
        Schema::create('plant_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plant_id');
            $table->enum('plant_stage', ['seedling', 'vegetative', 'budding','flowering','Ripening']);

            $table->integer('min_light_intensity');
            $table->integer('max_light_intensity');

            $table->float('min_humidity');
            $table->float('max_humidity');

            $table->float('min_temperature');
            $table->float('max_temperature');

            $table->integer('min_ppm');
            $table->integer('max_ppm');

            $table->float('min_ph');
            $table->float('max_ph');

            $table->float('min_water_temperature');
            $table->float('max_water_temperature');

            $table->integer('nitrogen');
            $table->integer('phosphorous');
            $table->integer('potassium');

            $table->foreign('plant_id')->references('plant_id')->on('plants_information')->onDelete('cascade');
            $table->unique(['plant_id', 'plant_stage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_requirements');
    }
};
