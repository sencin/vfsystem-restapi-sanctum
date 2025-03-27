<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table ="devices";

    protected $fillable =
    ['device_name',
    'device_state',
    'schedule_type',
    'start_time',
    'end_time',
    'interval',
    'interval_unit'];
}
