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
        Schema::create('towers', function (Blueprint $table) {
            $table->bigIncrements('tower_id');
            $table->string('tower_name');
            $table->integer('number_of_plants')->nullable();
            $table->unsignedBigInteger('plant_id');
            $table->mediumText('image')->nullable();
            $table->foreign('plant_id')->references('plant_id')->on('plants_information');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towers');
    }
};
