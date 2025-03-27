<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantHarvest extends Model
{
    use HasFactory;

    protected $table = "plant_harvest";
    protected $primaryKey = "plant_harvest_id";
    protected $fillable = ['transplant_id','harvest_quantity','date_of_harvest'];
    public $timestamps = false;
    public function plantTransplant(){
      return $this->belongsTo(PlantTransplant::class, 'transplant_id');
    }
}
