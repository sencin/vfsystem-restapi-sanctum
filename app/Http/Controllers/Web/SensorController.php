<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Repository\Sensor\SensorRepository;
use App\Http\Resources\SensorResource;
use App\Service\ImageService;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Http\Requests\Sensor\SensorRequest;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    protected SensorRepository $sensorRepository;
    protected ImageService $imageService;

    public function __construct(SensorRepository $sensorRepository, ImageService $imageService){
        $this->sensorRepository = $sensorRepository;
        $this->imageService = $imageService;
    }
    public function index(){
        $sensors = $this->sensorRepository->getAllSensors();
        Log::info('Sensors retrieved', ['count' => $sensors->count()]);
        return view('sensor.index', ['sensors'=> $sensors]);
    }
    public function create(){
        return view('sensor.create');
    }
    public function store(SensorRequest $request){
      try{
            $sensor = $request->validated();
            $sensor['image'] = $this->imageService->uploadImage($sensor['image'] ?? null);
            return $this->sensorRepository->createSensor($sensor);
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
        $sensor = Sensor::findOrFail($id);
        return view('sensor.show', ['currentSensor'=>$sensor]);
    }
    public function edit(string $id){
        $sensor = Sensor::findOrFail($id);
        return view('sensor.edit', ['currentSensor'=>$sensor]);
    }
    public function update(SensorRequest $request, Sensor $sensor){
        $validatedSensorRequest = $request->validated();
         if (isset($validatedSensorRequest['image'])) {
                $oldImage = $sensor->image ?? null;
                $this->imageService->deleteOldImage($oldImage);
                $validatedSensorRequest['image'] = $this->imageService->uploadImage($validatedSensorRequest['image']);
            }
      return $this->sensorRepository->updateSensor($validatedSensorRequest, $sensor);
    }
    public function destroy(Sensor $sensor){
        return $this->sensorRepository->deleteSensor($sensor);
    }
}
