<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\PlantStage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PlantTransplant;
use App\Service\PlantRequirementService;
use Illuminate\Support\Facades\Log;
class PlantStageController extends Controller
{
    public function create(string $id){

        $plantTransplant = PlantTransplant::findOrFail($id);
        $enumValues = DB::select('SHOW COLUMNS FROM plant_stages WHERE Field = "plant_stage"');
        if (!isset($enumValues[0]->Type)) {
            return [];
        }

        preg_match_all("/'([^']+)'/", $enumValues[0]->Type, $matches);

        return view('plantstage.create', [
            'plantTransplant' => $plantTransplant,
            'enumValues' => $matches[1],
        ]);
    }
    public function store(Request $request, PlantTransplant $planttransplant){
        $validated = $request->validate([
            'plant_stage' => 'required|string|max:255',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current_quantity' => 'nullable|integer|min:1',
        ]);

        $plantStage = $planttransplant->plantStages()->create($validated);

        return response()->json([
            'message' => 'Plant stage created successfully!',
            'data' => $plantStage,
        ], 201);
    }
    public function edit(string $id, string $plantstageId){
        $plantTransplant = PlantTransplant::findOrFail($id);
        $plantStage = $plantTransplant->plantStages()->findOrFail($plantstageId);

        return view('plantstage.edit', [
            'plantTransplant' => $plantTransplant,
            'plantStage' => $plantStage,
        ]);
    }
    public function update(Request $request, PlantTransplant $planttransplant, PlantStage $plantstage){

        $validated = $request->validate([
            'plant_stage' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'current_quantity' => 'nullable|integer',
        ]);
        $plantstage->update($validated);

        return response()->json([
            'message' => 'Plant stage Updated successfully!',
        ], 201);
    }
    public function destroy($planttransplant, $plantstage){
        $transplant = PlantTransplant::findOrFail($planttransplant);
        $requirement = PlantStage::findOrFail($plantstage);
        $requirement->delete();
        return redirect()->route('planttransplants.show', $transplant->transplant_id)
        ->with('success', 'Requirement updated successfully!');
    }
    public function updateStage(Request $request, PlantTransplant $planttransplant, PlantStage $plantstage)
    {
        $nextStage = null;
        $plantstage->active = 'no';
        $plantstage->save();

        Log::info("Plant Stage: " . $plantstage->plant_stage);
        // Determine the next stage using a switch statement
        switch($plantstage->plant_stage) {
            case 'seedling':
                $nextStage = 'vegetative';
                break;
            case 'vegetative':
                $nextStage = 'budding';
                break;
            case 'budding':
                $nextStage = 'flowering';
                break;
            case 'flowering':
                $nextStage = 'Ripening';
                break;
            default:
                Log::warning("Unexpected plant stage: " . $plantstage->plantstage);
                break;
        }

        // If a valid next stage was determined, create a new PlantStage entry
        if ($nextStage) {
            $plantstage->create([
                'transplant_id' => $planttransplant->transplant_id,
                'plant_stage'   => $nextStage,
                'active'        => 'yes']);
            return response()->json([
                'message' => 'Plant stage has been updated to ' . $nextStage,
            ], 201);
        } else {
            return response()->json([
                'message' => 'No valid next stage found.',
            ], 400);
        }
    }

}
