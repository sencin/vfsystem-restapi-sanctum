<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantInformation extends Model
{
    use HasFactory;
    protected $table ='plants_information';
    protected $primaryKey = 'plant_id';

    protected $fillable = [
        'plant_name',
        'species_family',
        'soil_type',
        'days_till_harvest',
        'plant_type',
        'image',
        'description',
        'plant_row_spacing'
    ];
    public $timestamps = false;

    public function cropSeasons(){
        return $this->hasMany(CropSeason::class, 'plant_id');
    }

    public function towers(){
       return $this->hasMany(Tower::class,'plant_id');
    }

    public function plantRequirements(){
        return $this->hasMany(PlantRequirement::class, 'plant_id');
    }
}
