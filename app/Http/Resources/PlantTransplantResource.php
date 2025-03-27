<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantTransplantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array{
        return [
            'tower_id' =>$this->tower_id,
            'plant_name' => $this->plantInformation->plant_name,
            'tower_name' => $this->tower_name
        ];
         //return parent::toArray($request);
    }
}
