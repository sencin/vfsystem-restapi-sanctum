<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantStage extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'transplant_id',
        'plant_stage',
        'start_date',
        'end_date',
        'current_quantity',
        'active'
    ];
    public function plantTransplant(){
        return $this->belongsTo(PlantTransplant::class, 'transplant_id');
    }
}
