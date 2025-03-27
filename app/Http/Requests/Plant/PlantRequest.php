<?php

namespace App\Http\Requests\Plant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PlantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('web')->check() || Auth::guard('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plant_name' => 'required|string|max:255',
            'species_family' => 'required|string|max:255',
            'soil_type'=>'required|string|max:255',
            'days_till_harvest' => 'required|integer',
            'plant_row_spacing' => 'required|integer',

            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'plant_type' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'plant_name.required' => 'The plant name is required.',
            'plant_name.string' => 'The plant name must be a valid string.',
            'plant_name.max' => 'The plant name cannot exceed 255 characters.',

            'species_family.required' => 'The species family is required.',
            'species_family.string' => 'The species family must be a valid string.',
            'species_family.max' => 'The species family cannot exceed 255 characters.',

            'soil_type.required' => 'The soil type is required.',
            'soil_type.string' => 'The soil type must be a valid string.',
            'soil_type.max' => 'The soil type cannot exceed 255 characters.',

            'days_till_harvest.required' => 'The number of days till harvest is required.',
            'days_till_harvest.integer' => 'The number of days till harvest must be an integer.',

            'plant_row_spacing.required' => 'The plant row spacing is required.',
            'plant_row_spacing.integer' => 'The plant row spacing must be an integer.',

            'image.required' => 'An image is required.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be in jpeg, png, or jpg format.',
            'image.max' => 'The image size cannot exceed 2MB.',

            'plant_type.string' => 'The plant type must be a valid string.',
        ];
    }
}
