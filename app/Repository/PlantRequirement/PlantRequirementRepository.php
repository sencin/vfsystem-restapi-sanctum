<?php

namespace App\Repository\PlantRequirement;

use App\Models\PlantInformation;
use App\Models\PlantRequirement;

class PlantRequirementRepository implements PlantRequirementRepositoryInterface {

    public PlantInformation $PlantInformation;

    public function __construct(PlantInformation $PlantInformation) {
        $this->PlantInformation = $PlantInformation;
    }
    public function getAllPlantRequirement() {
        return $this->PlantInformation::all();
    }
    public function getPlantRequirementsByPlantId($id) {
        $plantInfo = $this->PlantInformation::with('plantRequirements')->findOrFail($id);
        return $plantInfo->plantRequirements;
    }
    public function createPlantRequirement(array $data) {

    }
    public function updatePlantRequirement($id, array $data) {

    }
    public function deletePlantRequirement($id) {

    }
}
