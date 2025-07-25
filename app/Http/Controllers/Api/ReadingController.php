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
use Illuminate\Support\Facades\Log; 
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
        // --- 1. Validate and Retrieve Parameters ---
        // Expect 'month' (1-12) and 'year' (e.g., 2025)
        // Set defaults if not provided, or make them strictly required with a 400 response.
        // For dynamic month navigation, making them required is better.
        $month = (int) $request->query('month');
        $year = (int) $request->query('year');
        $intervalHours = (int) $request->query('interval', 1); // Default to 1 hour if not specified

        // Basic validation for month and year
        if (!$month || $month < 1 || $month > 12 || !$year) {
            Log::warning("Invalid or missing 'month' or 'year' parameter for average readings.");
            return response()->json([
                'error' => 'Valid "month" (1-12) and "year" are required parameters.'
            ], 400);
        }

        // --- 2. Calculate Start and End Dates for the Specified Month ---
        // Use DateTime objects for robust date calculations
        try {
            // First day of the specified month
            $dtStart = new DateTime("{$year}-{$month}-01 00:00:00");
            $startDate = $dtStart->format('Y-m-d H:i:s'); // Format for SQL

            // Last day of the specified month, including the last second
            $dtEnd = new DateTime("{$year}-{$month}-01 00:00:00");
            $dtEnd->modify('+1 month'); // Go to the first day of the next month
            $dtEnd->modify('-1 second'); // Subtract one second to get to the end of the current month
            $endDate = $dtEnd->format('Y-m-d H:i:s'); // Format for SQL

        } catch (\Exception $e) {
            Log::error("Date calculation error for month {$month}, year {$year}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to calculate date range for the specified month.'], 500);
        }

        // Log received parameters and calculated range for debugging
        Log::info("API Request: Averaged Readings for Month {$month}, Year {$year}. Calculated range: {$startDate} to {$endDate}, interval {$intervalHours} hours.");

        // --- 3. Build and Execute SQL Query ---
        $results = DB::select("
            SELECT
                sensor_id,
                DATE_FORMAT(MIN(record_date), '%Y-%m-%d %H:00:00') AS reading_time,
                ROUND(AVG(reading_value), 2) AS avg_reading
            FROM readings
            WHERE record_date >= ? AND record_date <= ? -- Filter by the calculated month range
            GROUP BY sensor_id, DATE(record_date), FLOOR(HOUR(record_date) / ?)
            ORDER BY MIN(record_date) ASC -- Ensure chronological order
        ", [$startDate, $endDate, $intervalHours]); // Pass the parameters as bindings

        // --- 4. Sensor Mappings ---
        $sensorNames = [
            1 => 'Temperature_DHT11',
            2 => 'Humidity_DHT11',
            3 => 'Analog_PH',
            4 => 'TDS_Meter',
            5 => 'TSL2561_Luminosity',
        ];

        // --- 5. Format Results for JSON Output ---
        $formattedResults = array_map(function ($result) use ($sensorNames) {
            return [
                'reading_time' => $result->reading_time, // e.g., "2025-01-16 01:00:00"
                'sensor_name' => $sensorNames[$result->sensor_id] ?? 'Unknown Sensor',
                'avg_reading' => $result->avg_reading,
            ];
        }, $results);

        Log::info("API Response: Returning " . count($formattedResults) . " average readings for Month {$month}, Year {$year}.");
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

    public function getNewReadings(Request $request) {
        $lastReadingId = $request->input('lastReadingId');
        $towerId = $request->input('towerId');
        $query = Reading::where('reading_id', '>', $lastReadingId);
        if ($towerId) {
            $query = $query->whereHas('sensor', function ($query) use ($towerId) {
                $query->where('tower_id', $towerId);
            });
        }
        $newReadings = $query->with('sensor')
                             ->orderBy('reading_id', 'desc')
                             ->take(50)
                             ->get()
                             ->reverse();

        $newReadings = $newReadings->map(function($reading) {
            return [
                'reading_id' => $reading->reading_id,
                'reading_value' => $reading->reading_value,
                'record_date' => $reading->record_date,
                'tower_id' => $reading->sensor->tower_id,
                'sensor_name' => $reading->sensor->sensor_name
            ];
        });

        if ($newReadings->isNotEmpty()) {
            return response()->json($newReadings->values()->toArray());
        }
        return response()->json(['message' => 'No new readings available.', 'lastFetchedId' => $lastReadingId], 204);
    }

}
