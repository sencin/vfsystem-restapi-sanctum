<?php

namespace App\Http\Requests\Harvest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class HarvestRequest extends FormRequest
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
            'transplant_id' => 'required|integer',
            'harvest_quantity' => 'required|integer',
            'date_of_harvest'=>'required|date'
        ];
    }

    public function messages()
    {
        return [
            'transplant_id.required' => 'The transplant ID is required.',
            'transplant_id.integer' => 'The transplant ID must be an integer.',
            'harvest_quantity.required' => 'Harvest quantity is required.',
            'harvest_quantity.integer' => 'Harvest quantity must be an integer.',
            'date_of_harvest.required' => 'The harvest date is required.',
            'date_of_harvest.date' => 'Please provide a valid date for the harvest.'
        ];
    }
}
