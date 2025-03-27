<?php

namespace App\Http\Requests\PlantRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PlantRequirementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plant_stage' => 'required|in:seedling,vegetative,budding,flowering,Ripening',
            'min_light_intensity' => 'required|integer',
            'max_light_intensity' => 'required|integer',
            'min_humidity' => 'required|numeric',
            'max_humidity' => 'required|numeric',
            'min_temperature' => 'required|numeric',
            'max_temperature' => 'required|numeric',
            'min_ppm' => 'required|integer',
            'max_ppm' => 'required|integer',
            'min_ph' => 'required|numeric',
            'max_ph' => 'required|numeric',
            'min_water_temperature' => 'required|numeric',
            'max_water_temperature' => 'required|numeric',
            'nitrogen' => 'required|integer',
            'phosphorous' => 'required|integer',
            'potassium' => 'required|integer',
        ];
    }
}
