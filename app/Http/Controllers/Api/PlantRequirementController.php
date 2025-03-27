<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlantRequirementResource;
use App\Repository\PlantRequirement\PlantRequirementRepositoryInterface;
use Illuminate\Http\Request;

class PlantRequirementController extends Controller
{
    protected PlantRequirementRepositoryInterface $repository;

    public function __construct(PlantRequirementRepositoryInterface $repository) {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $plantrequirement)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $plantrequirement){
        $requirement = $this->repository->getPlantRequirementsByPlantId($plantrequirement);
        return response()->json($requirement, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
