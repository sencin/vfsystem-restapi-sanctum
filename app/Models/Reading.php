<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
 use HasFactory;

 protected $table = 'readings';
 protected $primaryKey = 'reading_id';
 protected $fillable = ['reading_value', 'sensor_id', 'tower_id'];
 const CREATED_AT = 'record_date';
 const UPDATED_AT = null;

 public function tower()
    {
        return $this->belongsTo(Tower::class, 'tower_id');
    }

    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

 public function getRecordDateAttribute($value)
 {
     return Carbon::parse($value)
         ->setTimezone('Asia/Manila') // Convert to Philippine Time (UTC +8)
         ->format('Y-m-d H:i:s'); // Desired format without timezone
 }
}
