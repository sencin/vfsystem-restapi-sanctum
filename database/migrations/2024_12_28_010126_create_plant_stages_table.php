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
        Schema::create('plant_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transplant_id');
            $table->enum('plant_stage', ['seedling', 'vegetative', 'budding','flowering','Ripening']);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('active', ['yes', 'no'])->default('yes');
            $table->integer('current_quantity')->nullable();
            $table->foreign('transplant_id')->references('transplant_id')->on('plant_transplant')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_stages');
    }
};
