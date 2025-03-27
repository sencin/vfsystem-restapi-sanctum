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
        Schema::create('plants_information', function (Blueprint $table) {
            $table->bigIncrements('plant_id');
            $table->string('plant_name');
            $table->string('species_family');
            $table->string('soil_type');
            $table->integer('days_till_harvest');
            $table->enum('plant_type',['leafy_vegetable','fruit_crop'])->nullable();
            $table->mediumText('image')->nullable();
            $table->string('description')->nullable();
            $table->integer('plant_row_spacing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants_information');
    }
};
