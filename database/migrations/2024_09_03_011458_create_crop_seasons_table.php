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
        Schema::create('crop_seasons', function (Blueprint $table) {
            $table->bigIncrements('season_id');
            $table->enum('season_type',['harvest','planting'])->default('planting');
            $table->date('season_start');
            $table->date('season_end');
            $table->unsignedBigInteger('plant_id');
            $table->foreign('plant_id')->references('plant_id')->on('plants_information');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_seasons');
    }
};
