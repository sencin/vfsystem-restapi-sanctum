<?php

use App\Http\Controllers\Api\PlantHarvestController;
use App\Http\Controllers\Api\PlantInformationController;
use App\Http\Controllers\Api\PlantRequirementController;
use App\Http\Controllers\Api\ReadingController;
use App\Http\Controllers\Api\PlantStageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\TowerController;
use App\Http\Controllers\Web\PlantTransplantController;
use App\Http\Controllers\Api\UserAuth;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FileController;

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout',[UserAuth::class,'logout']);

    Route::get('/esp32websocketconfig',function(){
        return response()->json([
            'socketUrl'=> env('ESP32_CAMERA_WS')
        ]);
    });

    Route::apiResources([
        'plantinformations' => PlantInformationController::class,
        'plantrequirements' => PlantRequirementController::class,
        'sensors'           => SensorController::class,
        'towers'            => TowerController::class,
        'readings'          => ReadingController::class,
        'planttransplants'  => PlantTransplantController::class,
        'plantharvests'     => PlantHarvestController::class,
        'users'             => UserController::class
    ]);

    Route::apiResource('plantinformations.plantrequirements', PlantRequirementController::class);
    Route::apiResource('planttransplants.plantstages', PlantStageController::class);

    Route::post('planttransplants/{planttransplant}/plantstages/{plantstage}/updateStatus',
    [PlantStageController::class, 'updateStage']
    )->name('plantstages.updatestatus');

    Route::post('/users/{user}/approve', [UserController::class, 'approve']);
    Route::post('/users/{user}/reject', [UserController::class, 'reject']);
    Route::get('/pendingusers', [UserController::class,'getPendingUsersAPI']);

    Route::get('/getTowerId',[ReadingController::class, 'getTowerId']);
    Route::get('/plantinformations/availableplants', [PlantInformationController::class, 'getAvailablePlants'])->name('getAvailablePlants');
    Route::get('storage/image/{filename}', [FileController::class, 'show']);
});

Route::get('/availabletowers', [TowerController::class, 'showAllTowers']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'check.token.expiration']);

Route::post('/register', [UserAuth::class,'register']);
Route::post('/login',[UserAuth::class,'loginToken']);
Route::get('/average_readings', [ReadingController::class, 'getAverageReadings']);
Route::get('/sensor/average', [ReadingController::class, 'getSingleSensorAverages']);
