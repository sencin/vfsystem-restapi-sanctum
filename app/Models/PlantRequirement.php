<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantRequirement extends Model
{
    protected $table = 'plant_requirements';
    public $timestamps = false;
    protected $fillable = [
        'plant_stage',
        'plant_id',
        'min_light_intensity',
        'max_light_intensity',
        'min_humidity',
        'max_humidity',
        'min_temperature',
        'max_temperature',
        'min_ppm',
        'max_ppm',
        'min_ph',
        'max_ph',
        'min_water_temperature',
        'max_water_temperature',
        'nitrogen',
        'phosphorous',
        'potassium',
    ];

    public function plantInformation(){
       return $this->belongsTo(PlantInformation::class, 'plant_id');
    }
}
