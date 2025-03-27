<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plant\PlantRequest;
use App\Http\Requests\PlantRequirement\PlantRequirementRequest;
use App\Http\Requests\PlantRequirement\PlantRequirementUpdateRequest;
use App\Models\PlantInformation;
use App\Models\PlantRequirement;
use App\Service\PlantRequirementService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
class PlantRequirementController extends Controller
{
    // Display a listing of the requirements for a specific plant
    public function index($plantinformation)
    {
        $plant = PlantInformation::findOrFail($plantinformation);
        $requirements = $plant->requirements;
        return view('plantrequirements.index', compact('plant', 'requirements'));
    }

    // Show the form to create a new requirement for a specific plant
    public function create($plantinformation)
    {
        $stages = PlantRequirementService::getEnumStages("plant_requirements");
        // Retrieve the current plant and its requirements
        $plant = PlantInformation::with('plantRequirements')->findOrFail($plantinformation);
        // Extract existing plant stages for the current plant
        $existingStages = $plant->plantRequirements->pluck('plant_stage')->toArray();
        // Filter out stages that are already created
        $remainingStages = array_diff($stages, $existingStages);

        return view('plantrequirements.create', compact('plant', 'remainingStages'));
    }


    // Store a new requirement for a specific plant
    public function store(PlantRequirementRequest $request, $plantinformation)
    {
        Log::info('Raw Request Payload:', ['payload' => $request->all()]);

        // Log specific input data
        Log::info('Plant Stage:', ['plant_stage' => $request->input('plant_stage')]);

            $validated = $request->validated();

            // Find the associated plant information
            $plant = PlantInformation::findOrFail($plantinformation);

            // Add the `plant_id` to the validated data
            $validated['plant_id'] = $plant->id;

            // Create a new requirement record with the validated data and `plant_id`
            $plant->plantRequirements()->create($validated);

            return response()->json([
                'message' => 'Plant Requirement Added'
            ], 200);
    }


    // Show the form to edit a specific requirement
    public function edit($plant_id, $requirement_id)
    {
        Log::info('This is an informational message.');

        // Get the plant along with its plant requirements
        $plant = PlantInformation::with('plantRequirements')->findOrFail($plant_id);

        // Find the specific plant requirement based on the provided requirement ID
        $requirement = $plant->plantRequirements->find($requirement_id);

        if (!$requirement) {
            abort(404, 'Requirement not found');
        }

        // Pass the whole plant object (with its requirements) to the view
        return view('plantrequirements.edit', [
            'plant' => $plant,         // Plant with its plant requirements
            'requirement' => $requirement // Specific requirement to edit
        ]);
    }


    // Update a specific requirement
    public function update(PlantRequirementUpdateRequest $request, $plantinformation, $requirement_id)
    {
        // Validate the incoming request data
        $validated =  $request->validated();

        // Find the associated plant information
        $plant = PlantInformation::findOrFail($plantinformation);

        // Find the plant requirement record to update
        $requirement = $plant->plantRequirements()->findOrFail($requirement_id);

        // Update the existing requirement record with validated data
        $requirement->update($validated);

        // Redirect back with success message
        return response()->json([
            'message' => 'Plant Requirement Updated'
        ], 200);
    }


    // Delete a specific requirement
    public function destroy($plant_id, $requirement_id)
    {
        $plant = PlantInformation::findOrFail($plant_id);
        $requirement = PlantRequirement::findOrFail($requirement_id);
        $requirement->delete();
        return redirect()->route('plantinformations.show', $plant->plant_id)
        ->with('success', 'Requirement updated successfully!');
    }
}
