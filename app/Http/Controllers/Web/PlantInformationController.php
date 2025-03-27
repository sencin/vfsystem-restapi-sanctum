<?php

namespace App\Http\Controllers\Web;
use App\Models\PlantInformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plant\PlantRequest;
use App\Http\Resources\PlantInformationResource;
use App\Repository\Plant\PlantRepository;
use App\Service\ImageService;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Support\Facades\Log;
class PlantInformationController extends Controller{
    protected PlantRepository $plantRepository;
    protected ImageService $imageService;

    public function __construct(PlantRepository $plantRepository, ImageService $imageService){
        $this->plantRepository = $plantRepository;
        $this->imageService = $imageService;
    }

    public function create(){
        return view('plant.create');
    }
    public function store(PlantRequest $request){
       try{
            $plant = $request->validated();
            $plant['image'] = $this->imageService->uploadImage($plant['image'] ?? null);
            return $this->plantRepository->createPlant($plant);
       }
       catch(ValidationException $e){
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
       catch(AccessDeniedHttpException $e){
            return response()->json(['message' => 'Validation failed','errors' => $e->getMessage()], 422);
        }
    }
    public function show(string $id){
        $plantinformation = PlantInformation::with('plantRequirements')->findOrFail($id);
        $plantRequirements = $plantinformation->plantRequirements; // No toArray() here
        return view('plant.show', [
            'plant' => $plantinformation,
            'plantRequirements' => $plantRequirements,
        ]);
    }
    public function edit(string $id){
        $plantinformation = PlantInformation::findOrFail($id);
        return view('plant.edit', ['plantinformation' => $plantinformation]);
    }
    public function update(PlantRequest $request, PlantInformation $plantinformation){
       $plant = $request->validated();
       if(isset($plant['image'])){
            $oldImage = $plantinformation->image ?? null;
            $this->imageService->deleteOldImage($oldImage);
            $plant['image'] =$this->imageService->uploadImage($plant['image']);
       }
       return $this->plantRepository->updatePlant($plant, $plantinformation);
    }
    public function destroy(PlantInformation $plantinformation){
        $this->imageService->deleteOldImage($plantinformation['image']);
        return $this->plantRepository->deletePlant($plantinformation);
    }
    public function index(){
        $plants = $this->plantRepository->getAllPlants();
        return view('plant.index', ['plants'=> $plants]);
    }
    public function getAvailablePlants(){
        $plantinformation = PlantInformation::get();
        return ($plantinformation->count() > 0) ? PlantInformationResource::collection($plantinformation): response()->json(['message'=>'No record Available'],200);
    }
}
