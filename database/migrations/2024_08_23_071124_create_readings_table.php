<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->bigIncrements('reading_id');
            $table->unsignedBigInteger('reading_value');
            $table->timestamp('record_date')->useCurrent();
            $table->unsignedBigInteger('tower_id');
            $table->unsignedBigInteger('sensor_id');

            $table->foreign('sensor_id')->references('sensor_id')->on('sensors');
            $table->foreign('tower_id')->references('tower_id')->on('towers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readings');
    }
};
