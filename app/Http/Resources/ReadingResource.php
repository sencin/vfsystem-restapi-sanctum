<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class ReadingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return['reading_id'=>$this->reading_id,'reading_value'=>$this->reading_value,
        'record_date' => Carbon::parse($this->record_date)->setTimezone('UTC')->format('Y-m-d H:i:s'),
        'tower_id' => $this->tower_id,
        'tower_name'=>$this->tower->tower_name,
        'sensor_id' => $this->sensor_id,
        'sensor_name'=>$this->sensor->sensor_name,
    ];
    }
}
