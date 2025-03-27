<?php

namespace App\Repository\PlantHarvest;

use App\Http\Resources\PlantHarvestResource;
use App\Models\PlantHarvest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class PlantHarvestRepository{
    public function getAllHarvest(){
     return PlantHarvest::get();
    }
    public function getHarvestById(PlantHarvest $plantharvest){
     return new PlantHarvestResource($plantharvest);
    }
    public function createPlantHarvest(array $data){
        return PlantHarvest::create($data);
    }
    public function updatePlantHarvest(array $data, PlantHarvest $plantharvest){
        try{
            $plantharvest->update($data);
            return response()->json([
                'message' => 'Harvest Success'
            ], 200);
        }
        catch(ValidationException $e){

        }
    }
    public function deletePlantHarvest(PlantHarvest $plantharvest){

    }
}
