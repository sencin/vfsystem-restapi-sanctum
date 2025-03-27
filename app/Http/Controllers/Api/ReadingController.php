<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReadingResource;
use App\Models\Reading;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ReadingController extends Controller{

    public function index(){
        $reading = Reading::get();
        return ($reading->count() > 0) ? ReadingResource::collection($reading): response()->json(['message'=>'No record Available'],200);
    }

    public function store(Request $request){
        try {
            $validatedData = $request->validate(
            ['reading_value'=>'required|numeric', 'sensor_id'=>'required|integer']);
            $tower = $request->user();
            if (!$tower) {
                return response()->json(['message' => 'Unauthorized - Tower not found'], 401);
            }
            $validatedData['tower_id'] = $tower->tower_id;
            $reading = $tower->readings()->create($validatedData);
            return response()->json(['message' => 'Reading Added','data' => new ReadingResource($reading)],200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Failed All Fields Are Required','errors' => $e->errors()],422);
        }
    }

    public function show(Reading $reading){
        return new ReadingResource($reading);
    }

    public function update(){

    }
    public function destroy(){

    }

    public function getTowerId(Request $request){
      try{
            $tower = $request->user();
            if (!$tower) {
                return response()->json(['message' => 'Unauthorized - Tower not found'], 401);
            }
            return response()->json(['tower_id' => $tower->tower_id], 200);
        }
       catch (ValidationException $e) {
           return response()->json(['message' => 'Validation Failed All Fields Are Required','errors' => $e->errors()],422);
       }
    }

    public function getAverageReadings(Request $request)
    {
        $startDate = $request->query('start_date', '2025-01-01'); // Default start date
        $endDate = $request->query('end_date', '2025-01-21'); // Default end date

        // Raw query to calculate average readings
        $results = DB::select("
        SELECT
            DATE(record_date) AS reading_date,
            sensor_id,
            AVG(reading_value) AS avg_reading
        FROM readings
        WHERE record_date BETWEEN ? AND ?
        GROUP BY reading_date, sensor_id
        ORDER BY reading_date ASC
    ", [$startDate, $endDate]);
    $sensorNames = [
        1 => 'Humidity_DHT11',
        2 => 'Temperature_DHT11',
        3 => 'TSL2561_Luminosity',
        4 => 'TDS_Meter',
        5 => 'Analog_PH',
    ];

    $formattedResults = array_map(function ($result) use ($sensorNames) {
        return [
            'reading_date' => $result->reading_date,
            'sensor_name' => $sensorNames[$result->sensor_id] ?? 'Unknown Sensor',
            'avg_reading' => $result->avg_reading,
        ];
    }, $results);

    return response()->json($formattedResults);
    }
}
