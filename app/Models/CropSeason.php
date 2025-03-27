<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropSeason extends Model
{
    use HasFactory;
    protected $table ="crop_seasons";
    protected $fillable = ['season_type','season_start','season_end','plant_id'];
    protected $primaryKey = "season_id";

    public function plantInformation(){
        return $this->belongsTo(PlantInformation::class, 'plant_id');
    }

}
