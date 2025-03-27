<?php

namespace App\Repository\Plant;
use App\Http\Resources\PlantInformationResource;
use App\Models\PlantInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class PlantRepository
{
    public function getAllPlants(){
        return PlantInformation::get();
    }
    public function getPlantById(PlantInformation $plantinformation){
        return new PlantInformationResource($plantinformation);
    }
    public function createPlant(array $data){
        try {
                $plantinformation = PlantInformation::create($data);
                return response()->json([
                    'message' => 'Plant ' .$plantinformation->plant_name . ' is created',
                ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
    public function updatePlant(array $data, PlantInformation $plantinformation){
        try {
            $plantinformation->update($data);
            return response()->json([
                'message' => 'Plant information updated successfully',
                'data' => new PlantInformationResource($plantinformation)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deletePlant(PlantInformation $plantinformation){
        $plantName = $plantinformation->plant_name;
        $plantinformation->delete();
        return response()->json([
            'message' => "Plant $plantName is Deleted"
        ], 200);
    }
}
