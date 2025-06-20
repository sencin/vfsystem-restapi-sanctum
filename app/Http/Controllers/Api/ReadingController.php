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
use DateTime;
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

        // Query to calculate daily average per sensor
        $results = DB::select("
            SELECT
                DATE(record_date) AS reading_date,
                sensor_id,
                ROUND(AVG(reading_value), 2) AS avg_reading
            FROM readings
            WHERE record_date BETWEEN ? AND ?
            GROUP BY reading_date, sensor_id
            ORDER BY reading_date ASC
        ", [$startDate, $endDate]);

        // ✅ Corrected sensor mappings
        $sensorNames = [
            1 => 'Temperature_DHT11',
            2 => 'Humidity_DHT11',
            3 => 'Analog_PH',        // ✅ Now correctly mapped
            4 => 'TDS_Meter',        // ✅ Now correctly mapped
            5 => 'TSL2561_Luminosity', // ✅ Correct Luminosity mapping
        ];

        // Format results
        $formattedResults = array_map(function ($result) use ($sensorNames) {
            return [
                'reading_date' => $result->reading_date,
                'sensor_name' => $sensorNames[$result->sensor_id] ?? 'Unknown Sensor',
                'avg_reading' => $result->avg_reading,
            ];
        }, $results);

        return response()->json($formattedResults);
    }

    public function getSingleSensorAverages(Request $request)
    {
        $sensorId = (int) $request->query('sensor_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $intervalHours = (int) $request->query('interval', 1); // Default 1 hour

        // --- Input Validation ---
        if (!$sensorId || !$startDate || !$endDate) {
            return response()->json([
                'error' => 'Parameters "sensor_id", "start_date", and "end_date" are required.'
            ], 400);
        }

        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid date format. Use "YYYY-MM-DD HH:MM:SS".'
            ], 400);
        }

        // --- Execute Query ---
        $results = DB::select("
            SELECT
                DATE_FORMAT(MIN(record_date), '%Y-%m-%d %H:00:00') AS reading_time,
                ROUND(AVG(reading_value), 2) AS avg_reading
            FROM readings
            WHERE sensor_id = ?
            AND record_date BETWEEN ? AND ?
            GROUP BY DATE(record_date), FLOOR(HOUR(record_date) / ?)
            ORDER BY MIN(record_date) ASC
        ", [
            $sensorId,
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s'),
            $intervalHours
        ]);

        // --- Format Output ---
        $data = array_map(fn($row) => [
            'reading_time' => $row->reading_time,
            'avg_reading' => $row->avg_reading,
        ], $results);

        return response()->json($data);
    }

}
