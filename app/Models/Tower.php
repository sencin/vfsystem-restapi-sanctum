<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Tower extends Model
{
    use HasFactory, HasApiTokens;
    protected $table ='towers';
    protected $primaryKey = 'tower_id';
    protected $fillable =['tower_name','plant_id','number_of_plants','image'];
    public $timestamps = false;

    public function readings(){
        return $this->hasMany(Reading::class, 'tower_id');
    }
    public function plantMonitors(){
        return $this->hasMany(PlantTransplant::class, 'tower_id');
    }
    //this code seems sussy. get back here if some error happened.
    public function plantInformation(){
       return $this->belongsTo(PlantInformation::class, 'plant_id');
    }
}
