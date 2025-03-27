<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SensorResource;
use Illuminate\Http\Request;
use App\Models\Sensor;
use Illuminate\Validation\ValidationException;
class SensorController extends Controller
{

    public function index(){
        $sensor = Sensor::get();
        if($sensor->count() > 0){
           return SensorResource::collection($sensor);
        }
        else{
            return response()->json(['message'=>'No record Available'],200);
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate(['sensor_name' => 'required|string|max:255','sensor_description' => 'required|string']);
            $sensor = Sensor::create($validatedData);
            return response()->json(['message' => 'Sensor Created','data' => new SensorResource($sensor)],200);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Failed All Fields Are Required','errors' => $e->errors()],422);
        }
    }
    public function show(Sensor $sensor){
        return new SensorResource($sensor);
    }
    public function update(Request $request, Sensor $sensor){
        try {
            $validatedData = $request->validate(['sensor_name' => 'required|string|max:255','sensor_description' => 'required|string']);
            $sensor->update($validatedData);
            return response()->json(['message' => 'Sensor Updated','data' => new SensorResource($sensor)],200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Failed All Fields Are Required Before Update','errors' => $e->errors()],422);
        }
    }
    public function destroy(Sensor $sensor){
        $sensor->delete();
        return response()->json(['message' => 'Sensor Deleted'],200);
    }
}
