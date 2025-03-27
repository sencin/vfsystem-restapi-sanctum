<?php

namespace App\Http\Controllers\Web;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TowerResource;
use App\Models\Tower;
use Illuminate\Support\Facades\Log;
use App\Repository\Tower\TowerRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Tower\TowerRequest;
use App\Service\ImageService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Support\Facades\DB;
class TowerController extends Controller
{
    protected TowerRepository $towerRepository;
    protected ImageService $imageService;

    public function __construct(TowerRepository $towerRepository, ImageService $imageService){
      $this->towerRepository = $towerRepository;
      $this->imageService = $imageService;
    }
    public function index(){
        $towers = $this->towerRepository->getAllTowers()->load('plantInformation');
        return view('tower.index', ['towers'=>$towers]);
    }
    public function create(){
        return view('tower.create');
    }
    public function store(TowerRequest $request){
        try{
        $tower = $request->validated();
        //$tower['number_of_plants'] = $tower['number_of_plants'] ?? 0;
        $tower['image'] = $this->imageService->uploadImage($tower['image'] ?? null);
        return $this->towerRepository->createTower($tower);
        }
        catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        catch(AccessDeniedHttpException $e){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->getMessage()
            ], 422);
        }
    }
    public function show(string $id){
      $tower = Tower::with('plantInformation')->findOrFail($id);
      return view('tower.show', ['currentTower'=>$tower]);
    }
    public function edit(string $id){
      $tower = Tower::findOrFail($id);
      return view('tower.edit', ['currentTower'=>$tower]);
    }
    public function update(TowerRequest $request, Tower $tower){
        try {
          //  Log::info('Tower ID: ' . $tower);
            $validatedTowerRequest = $request->validated();
           // Log::info('Validated data:', ['data' => $validatedTowerRequest]);
            if (isset($validatedTowerRequest['image'])) {
                $oldImage = $tower->image ?? null;
                $this->imageService->deleteOldImage($oldImage);
                $validatedTowerRequest['image'] = $this->imageService->uploadImage($validatedTowerRequest['image']);
            }
            return $this->towerRepository->updateTower($validatedTowerRequest, $tower);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed. All Fields Are Required Before Update',
                'errors' => $e->errors()
            ], 422);
        } catch (AccessDeniedHttpException $e) {
            return response()->json([
                'message' => 'Access denied',
                'errors' => $e->getMessage()
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Tower $tower){
        $this->imageService->deleteOldImage($tower['image']);
        return $this->towerRepository->deleteTower($tower);
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
