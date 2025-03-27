<?php

namespace App\Http\Controllers\Api;

use App\Models\PlantInformation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PlantInformationResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class PlantInformationController extends Controller
{
    public function index(){
        $plantinformation = PlantInformation::get();
        return ($plantinformation->count() > 0) ? PlantInformationResource::collection($plantinformation): response()->json(['message'=>'No record Available'],200);
    }
    public function store(Request $request){
        try {
            $validatedData = $request->validate([
            'plant_name'=>'required|string|max:255',
            'temperature'=>'required|integer',
            'humidity'=>'required|integer',
            'days_to_harvest'=>'required|integer',
            'nitrogen'=>'required|numeric',
            'phosphorus'=>'required|numeric',
            'potassium'=>'required|numeric']);

            if (Auth::guard('web')->check() || Auth::guard('sanctum')->check()) {
                $user = Auth::user();
                $validatedData['user_id'] = $user->user_id; // Access user_id field
                $plantinformation = PlantInformation::create($validatedData);
                return response()->json(['message' => 'Plant Information Created','data' => new PlantInformationResource($plantinformation)], 200);
            } else {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Failed All Fields Are Required','errors' => $e->errors()],422);
        }
    }
    public function show(PlantInformation $plantinformation){
        return new PlantInformationResource($plantinformation);
    }
    public function update(Request $request, PlantInformation $plantinformation){
        try {
        $validatedData = $request->validate(['plant_name'=>'required|string|max:255','humidity'=>'required|integer','days_to_harvest'=>'required|integer','nitrogen'=>'required|numeric','phosphorus'=>'required|numeric','potassium'=>'required|numeric']);
        $plantinformation->update($validatedData);
        return response()->json(['message' => 'Plant information Updated','data' => new PlantInformationResource($plantinformation)],200);
        } catch (ValidationException $e) {
        return response()->json(['message' => 'Validation Failed All Fields Are Required','errors' => $e->errors()],422);
        }
    }
    public function destroy(PlantInformation $plantinformation){
        $plantinformation->delete();
        return response()->json(['message' => "Plant $plantinformation->plant_name is Deleted",],200);
    }
}
