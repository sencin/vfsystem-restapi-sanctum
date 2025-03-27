<?php
namespace App\Repository\Sensor;
use App\Models\Sensor;
use App\Http\Resources\SensorResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SensorRepository{
    public function getAllSensors(){
        return Sensor::get();
    }
    public function getSensorById(Sensor $sensor){
        return new SensorResource($sensor);
    }
    public function createSensor(array $data){
        try {
                $sensor = Sensor::create($data);
                return response()->json([
                    'message' => 'Sensor Information Created Using Service',
                    'data' => new SensorResource($sensor)
                ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function updateSensor(array $data, Sensor $sensor){
        try {
            $sensor->update($data);
            return response()->json([
                'message' => 'Sensor information Updated',
                'data' => new SensorResource($sensor)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteSensor(Sensor $sensor){
        $sensorname = $sensor->plant_name;
        $sensor->delete();
        return response()->json([
            'message' => "Sensor $sensorname is Deleted"
        ], 200);
    }
}
