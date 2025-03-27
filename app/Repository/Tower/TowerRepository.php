<?php

namespace App\Repository\Tower;

use App\Http\Resources\TowerResource;
use App\Models\Tower;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TowerRepository{
    public function getAllTowers(){
        return Tower::get();
    }
    public function getTowerById(Tower $tower){
        return new TowerResource($tower);
    }
    public function createTower(array $data){
        try {
            $tower = Tower::create($data);
            $token = $tower ->createToken($tower->tower_name);
                return response()->json([
                    'message' => "Tower Name {$tower->tower_name} is Created using service",
                    'Token' => $token->plainTextToken,
                    'data' => new TowerResource($tower),
                    'plant_id'=> $tower->plant_id,
                    'number_of_plants'=> $tower->number_of_plants,
                ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
    public function updateTower(array $data, Tower $tower){
        try{

            $tower->update($data);

            return response()->json([
                'message' => 'Tower Updated',
                'data' => new TowerResource($tower)
            ], 200);
        }
        catch(ValidationException $e){

        }
    }
    public function deleteTower(Tower $tower){
        try {
            $tower->delete();
            return response()->json(['message' => "Tower Deleted"],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

}
