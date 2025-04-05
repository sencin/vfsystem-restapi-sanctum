<?php
use App\Http\Controllers\Api\PlantInformationController;
use App\Http\Controllers\Api\PlantRequirementController;
use App\Http\Controllers\Api\ReadingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\TowerController;
use App\Http\Controllers\Web\PlantTransplantController;
use App\Http\Controllers\Web\UserAuth;
use App\Http\Controllers\Web\UserController;
Route::middleware('auth:sanctum')->group(function(){

    Route::apiResources([
        'plantinformations' => PlantInformationController::class,
        'plantrequirements' => PlantRequirementController::class,
        'sensors'           => SensorController::class,
        'towers'            => TowerController::class,
        'readings'          => ReadingController::class,
        'planttransplants'  => PlantTransplantController::class
    ]);

    Route::post('/users/{user}/approve', [UserController::class, 'approve']);
    Route::post('/users/{user}/reject', [UserController::class, 'reject']);
    Route::get('/pendingusers', [UserController::class,'getPendingUsersAPI']);

    // Route::apiResource('plantinformations', PlantInformationController::class);
    // Route::apiResource('plantrequirements', PlantRequirementController::class);
    // Route::apiResource('sensors', SensorController::class);
    // Route::apiResource('towers', TowerController::class);
    // Route::apiResource('readings', ReadingController::class);
    // Route::apiResource('planttransplants', PlantTransplantController::class);

    Route::get('/getTowerId',[ReadingController::class, 'getTowerId']);
});

Route::get('/availabletowers', [TowerController::class, 'showAllTowers']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserAuth::class,'register']);
Route::post('/login',[UserAuth::class,'login']);
Route::post('/logout',[UserAuth::class,'logout'])->middleware('auth:sanctum');
Route::get('/average_readings', [ReadingController::class, 'getAverageReadings']);
