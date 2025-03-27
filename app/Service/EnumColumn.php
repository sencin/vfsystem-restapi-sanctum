<?php
 namespace App\Service;
 use Illuminate\Support\Facades\DB;
class EnumColumn
{
    /**
     * Get all possible stages from the ENUM column
     *
     * @return array
     */
    public static function getEnumStages($tablename, $field): array
    {
        $query = "SHOW COLUMNS FROM `$tablename` WHERE Field = ?";
        $result = DB::select($query, [$field]);

        if (!isset($result[0]->Type)) {
            return [];
        }
        preg_match_all("/'([^']+)'/", $result[0]->Type, $matches);
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
