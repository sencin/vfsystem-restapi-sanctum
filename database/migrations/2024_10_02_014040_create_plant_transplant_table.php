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
        Schema::create('plant_transplant', function (Blueprint $table) {
            $table->bigIncrements('transplant_id');

            $table->unsignedBigInteger('tower_id');
            $table->date('transplant_date');
            $table->integer('initial_quantity');
            $table->enum('status', ['in_progress', 'completed', 'failed'])->default('in_progress');
            $table->foreign('tower_id')->references('tower_id')->on('towers')->onDelete('cascade');
        });

        Schema::create('plant_harvest', function (Blueprint $table) {
            $table->bigIncrements('plant_harvest_id');
            $table->unsignedBigInteger('transplant_id');
            $table->integer('harvest_quantity');
            $table->date('date_of_harvest');
            $table->foreign('transplant_id')->references('transplant_id')->on('plant_transplant')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_transplant');
        Schema::dropIfExists('harvest');
    }
};
