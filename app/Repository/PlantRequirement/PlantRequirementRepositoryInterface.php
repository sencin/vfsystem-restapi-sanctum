<?php

namespace App\Repository\PlantRequirement;

interface PlantRequirementRepositoryInterface{
    public function getAllPlantRequirement();
    public function getPlantRequirementsByPlantId($id);
    public function createPlantRequirement(array $data);
    public function updatePlantRequirement($id, array $data);
    public function deletePlantRequirement($id);
}
