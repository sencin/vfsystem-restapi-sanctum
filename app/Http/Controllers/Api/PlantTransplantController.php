<?php

namespace App\Http\Controllers\Web;
use App\Models\PlantTransplant;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlantTransplant\PlantTransplantRequest;
use App\Http\Resources\PlantTransplantResource;
use App\Repository\PlantTransplant\PlantTransplantRepository;
use App\Repository\Tower\TowerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlantTransplantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected TowerRepository $towerRepository;
    protected PlantTransplantRepository $plantTransplantRepository;

    public function __construct(TowerRepository $towerRepository, PlantTransplantRepository $plantTransplantRepository){
      $this->towerRepository = $towerRepository;
      $this->plantTransplantRepository = $plantTransplantRepository;
    }

    public function index()
    {
        $towers = $this->towerRepository->getAllTowers()->load('plantInformation');

        $monitored = PlantTransplant::with(['tower.plantInformation.plantRequirements'])
            ->where('status', 'in_progress')
            ->get();

        $arrayTower = PlantTransplantResource::collection($towers);

        return response()->json([
            'towers' => $arrayTower,
            'tabletowers' => $monitored
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PlantTransplantRequest $request)
    {
        $validated = $request->validated();
        $result = $this->plantTransplantRepository->createPlantTransplant($validated);

        return response()->json([
            'message' => 'Success. Plant is Being Monitored',
            'data' => $result
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plantTransplantDetails = PlantTransplant::with(['plantStages' => function ($query) {
            $query->where('active', 'no');
        }])->findOrFail($id);

        $currentPlantStage = $plantTransplantDetails->plantStages()
            ->where('active', 'yes')
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'plantTransplantDetails' => $plantTransplantDetails,
            'currentPlantStage' => $currentPlantStage
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlantTransplantRequest $request, PlantTransplant $planttransplant)
    {
        Log::info('Model: ' . $planttransplant);
        $validatedRequest = $request->validated();
        Log::info('Validated data:', ['data' => $validatedRequest]);

        $updated = $this->plantTransplantRepository->updatePlantTransplant($validatedRequest, $planttransplant);

        $status = $updated ? 200 : 500;
        $message = $updated ? 'Plant Transplant Updated' : 'Failed to update Plant Transplant';

        return response()->json(['message' => $message], $status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
