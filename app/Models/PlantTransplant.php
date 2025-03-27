<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantTransplant extends Model
{
    use HasFactory;

    protected $table = "plant_transplant";
    protected $primaryKey = "transplant_id";
    protected $fillable = [
        'tower_id',
        'transplant_date',
        'initial_quantity',
        'status',
    ];
    public $timestamps = false;

    public function tower(){
        return $this->belongsTo(Tower::class, 'tower_id');
    }

    public function plantHarvests(){
        return $this->hasMany(PlantHarvest::class,'transplant_id');
    }
    public function plantStages(){
        return $this->hasMany(PlantStage::class,'transplant_id');
    }
}
