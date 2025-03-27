<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TowerResource;
use App\Models\Tower;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TowerController extends Controller
{
    //
    public function index(){
     $tower = Tower::get();
     if($tower->count() > 0){
        return TowerResource::collection($tower);
     }
     else{
        return response()->json(['message'=>'No record Available'],200);
     }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate(['tower_name' => 'required|string|max:255']);
            $tower = Tower::create($validatedData);
            $token = $tower ->createToken($request->tower_name);

            return response()->json(['message' => "Tower Name $tower->tower_name is Created",'Token'=>$token->plainTextToken,'data' => new TowerResource($tower)],200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Failed All Fields Are Required','errors' => $e->errors()],422);
        }
    }
    public function show(Tower $tower){
        return new TowerResource($tower);
    }
    public function update(Request $request, Tower $tower){
        try {
            $validatedData = $request->validate(['tower_name' => 'required|string|max:255']);
            $tower->update($validatedData);
            return response()->json(['message' => 'Tower Updated','data' => new TowerResource($tower)],200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Failed All Fields Are Required Before Update','errors' => $e->errors()],422);
        }
    }

    public function destroy(Tower $tower){
        $tower->delete();
        return response()->json(['message' => "Tower Deleted"],200);
    }

    public function showAllTowers()
    {
        $query = "
                SELECT readings.reading_id, readings.reading_value, readings.record_date, readings.tower_id, readings.sensor_id, sensors.sensor_name
                FROM readings
                INNER JOIN (
                    SELECT tower_id, sensor_id, MAX(record_date) AS latest_record_date
                    FROM readings
                    GROUP BY tower_id, sensor_id
                ) latest_readings
                ON readings.tower_id = latest_readings.tower_id
                AND readings.sensor_id = latest_readings.sensor_id
                AND readings.record_date = latest_readings.latest_record_date
                INNER JOIN sensors ON sensors.sensor_id = readings.sensor_id
                ORDER BY readings.tower_id, readings.record_date DESC
            ";

        // Get all readings for all towers
        $readings = DB::select($query);

        // Get all tower details
        $towers = DB::table('towers')->get();

        $towerReadings = [];

        foreach ($towers as $tower) {
            $towerReadings[] = [
                'tower_id' => $tower->tower_id,
                'tower_name' => $tower->tower_name,
                'readings' => array_values(array_filter($readings, function ($reading) use ($tower) {
                    return $reading->tower_id == $tower->tower_id;
                }))
            ];
        }

        return response()->json([
            'towers' => $towerReadings
        ]);
    }
}
