<?php

namespace App\Http\Requests\PlantStage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class PlantStageRequest extends FormRequest
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
            'transplant_id' => 'required|exists:plant_transplant,transplant_id',  // Ensure transplant_id exists in plant_transplant table
            'plant_stage' => 'required|in:seedling,vegetative,budding,flowering,Ripening', // Plant stage enum validation
            'start_date' => 'required|date', // Date format validation
            'end_date' => 'nullable|date|after:start_date', // End date must be after the start date
            'current_quantity' => 'nullable|integer|min:0', // Ensure quantity is a positive integer
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'transplant_id.required' => 'The transplant ID is required.',
            'transplant_id.exists' => 'The transplant ID must exist in the transplant table.',
            'plant_stage.required' => 'The plant stage is required.',
            'plant_stage.in' => 'The plant stage must be one of the following: seedling, vegetative, budding, flowering, Ripening.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after' => 'The end date must be after the start date.',
            'current_quantity.integer' => 'The current quantity must be an integer.',
            'current_quantity.min' => 'The current quantity must be at least 0.',
        ];
    }
}
