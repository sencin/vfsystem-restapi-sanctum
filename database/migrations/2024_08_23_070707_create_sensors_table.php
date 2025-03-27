<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sensor;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->bigIncrements('sensor_id');
            $table->string('sensor_name');
            $table->mediumText('sensor_description')->nullable();
            $table->mediumText('image')->nullable();
        });

        $temp = new Sensor([
            'sensor_id' => 1,
            'sensor_name' => 'Temperature_DHT11',
        ]);
        $temp->save();

        $humid = new Sensor([
            'sensor_id' => 2,
            'sensor_name' => 'Humidity_DHT11',
        ]);
        $humid->save();

        $ph = new Sensor([
            'sensor_id' => 3,
            'sensor_name' => 'Analog_PH',
        ]);
        $ph->save();

        $tds = new Sensor([
            'sensor_id' => 4,
            'sensor_name' => 'TDS_Meter',
        ]);
        $tds->save();

        $lumen = new Sensor([
            'sensor_id' => 5,
            'sensor_name' => 'TSL2561_Luminosity',
        ]);
        $lumen->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
