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
    public function createPlantTransplant(array $data) {
      $transplant =  PlantTransplant::create($data);
      $transplant->plantStages()->create(['plant_stage' => 'seedling']);

        return response()->json([
            'message' => "Success. Plant is Being Monitored"
        ], 200);
    }

    public function updatePlantTransplant(array $data, PlantTransplant $plantTransplant){
        try{
            $plantTransplant->update($data);
            return response()->json([
                'message' => 'Plant Transplant Updated'
            ], 200);
        }
        catch(ValidationException $e){

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
