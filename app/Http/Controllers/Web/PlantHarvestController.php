<?php

namespace App\Http\Controllers\Web;

use App\Models\PlantHarvest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Harvest\HarvestRequest;
use App\Http\Resources\PlantHarvestResource;
use App\Repository\PlantHarvest\PlantHarvestRepository;
use App\Repository\PlantTransplant\PlantTransplantRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class PlantHarvestController extends Controller
{
    protected PlantHarvestRepository $HarvestRepository;
    protected PlantTransplantRepository $TransplantRepository;

    public function __construct(PlantHarvestRepository $HarvestRepository, PlantTransplantRepository $TransplantRepository) {
     $this->HarvestRepository = $HarvestRepository;
     $this->TransplantRepository = $TransplantRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $harvestQuery = $this->HarvestRepository->getAllHarvest()->load('plantTransplant');
       $arrayHarvest = PlantHarvestResource::collection($harvestQuery);
       return view('plantharvest.index', ['plantharvest'=> $arrayHarvest]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HarvestRequest $request){
        try {
            DB::beginTransaction();
            $validated = $request->validated();

            $PlantHarvest = $this->HarvestRepository->createPlantHarvest($validated);

            if ($PlantHarvest === null) {
                throw new \Exception('Failed to create plant harvest');
            }

            $isProgressChanged = $this->TransplantRepository->changePlantStatus($PlantHarvest->transplant_id, "completed");
            if (!$isProgressChanged) {
                throw new \Exception('Failed to update plant progress');
            }
            // Log::info('Is this Yow?');
            // $isStatusChanged = $this->TransplantRepository->changeActiveStatus($PlantHarvest->transplant_id, "no");
            // if (!$isStatusChanged) {
            //     throw new \Exception('Failed to update plant status');
            // }
            DB::commit();
            return response()->json(['message' => 'Harvest Success'], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Validation failed','errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
           // Log::error('Error occurred: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred','error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
      $harvestrecord = PlantHarvest::with(['plantTransplant.tower.plantInformation'])->findOrFail($id);
      return view('plantharvest.show',['harvestrecord'=>$harvestrecord]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlantHarvest $harvest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HarvestRequest $request, PlantHarvest $harvest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlantHarvest $harvest)
    {
        //
    }
}
