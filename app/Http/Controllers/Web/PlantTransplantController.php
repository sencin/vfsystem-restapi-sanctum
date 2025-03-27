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

    public function index(){
        $towers = $this->towerRepository->getAllTowers()->load('plantInformation');
        $monitored = PlantTransplant::with(['tower.plantInformation.plantRequirements'])
        ->where('status', 'in_progress')
        ->get();
        $arrayTower = PlantTransplantResource::collection($towers);
        return view('planttransplant.index',['towers'=>$arrayTower, 'tabletowers'=>$monitored]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlantTransplantRequest $request){
        $validated = $request->validated();
        return $this->plantTransplantRepository->createPlantTransplant($validated);
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

        return view('planttransplant.show', [
            'plantTransplantDetails' => $plantTransplantDetails,
             'currentPlantStage'=> $currentPlantStage
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){
        $plantTransplant = PlantTransplant::with(['tower'])->findOrFail($id);
        return view('planttransplant.edit', ['plantTransplant' => $plantTransplant]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlantTransplantRequest $request, PlantTransplant $planttransplant){
            Log::info('Model: ' . $planttransplant);
            $validatedRequest = $request->validated();
            Log::info('Validated data:', ['data' => $validatedRequest]);
            return $this->plantTransplantRepository->updatePlantTransplant($validatedRequest, $planttransplant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
