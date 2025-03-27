<?php
 namespace App\Service;
 use Illuminate\Support\Facades\DB;
class PlantRequirementService
{
    /**
     * Get all possible stages from the ENUM column
     *
     * @return array
     */
    public static function getEnumStages($table): array
    {
        $enumValues = DB::select('SHOW COLUMNS FROM ' . $table . ' WHERE Field = "plant_stage"');

        if (!isset($enumValues[0]->Type)) {
            return [];
        }

        preg_match_all("/'([^']+)'/", $enumValues[0]->Type, $matches);

        return $matches[1] ?? [];
    }

    /**
     * Get remaining stages that are not yet assigned to the plant
     *
     * @param array $allStages
     * @param array $existingStages
     * @return array
     */
    public static function getRemainingStages(array $allStages, array $existingStages): array
    {
        return array_diff($allStages, $existingStages);
    }
}
