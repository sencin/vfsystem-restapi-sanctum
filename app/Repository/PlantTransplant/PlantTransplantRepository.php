<?php

namespace App\Repository\PlantTransplant;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\PlantTransplantResource;
use App\Models\PlantTransplant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\PlantStage;
use Illuminate\Support\Facades\Date;
class PlantTransplantRepository{

    public function getAllTransplant(){

    }
    public function getTransplantById(PlantTransplant $plantTransplant){

    }
    public function createPlantTransplant(array $data)
    {
        $transplant = PlantTransplant::create($data);
        $transplant->plantStages()->create(['plant_stage' => 'seedling']);

        return $transplant; // or return true/false if you want only status
    }

    public function updatePlantTransplant(array $data, PlantTransplant $plantTransplant)
    {
        try {
            $plantTransplant->update($data);
            return true; // or return $plantTransplant if needed
        } catch (ValidationException $e) {
            // Optionally handle/log exception, or let it bubble up
            return false;
        }
    }

    public function deletePlantTransplant(PlantTransplant $plantTransplant){

    }

    public function changePlantStatus(string $id, string $value): bool {
        $plantTransplant = PlantTransplant::findOrFail($id);
        $plantTransplant->status = $value;
        return $plantTransplant->save();
    }

    public function changeActiveStatus(string $id, string $value): bool {
        $plantTransplant = PlantTransplant::findOrFail($id);
        $plantTransplant->active = $value;
        return $plantTransplant->save();
    }
}
