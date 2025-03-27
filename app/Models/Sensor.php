<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;
    protected $table ='sensors';
    protected $primaryKey = 'sensor_id';
    protected $fillable =['sensor_name','sensor_description','image'];
    public $timestamps = false;

    public function readings()
    {
        return $this->hasMany(Reading::class,'sensor_id');
    }
}
