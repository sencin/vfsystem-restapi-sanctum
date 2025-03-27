<?php

namespace App\Http\Requests\PlantTransplant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PlantTransplantRequest extends FormRequest
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
            'tower_id'=>"required|integer",
            'transplant_date'=>'required|date',
            'initial_quantity'=>'required|integer',
            'status' => 'in:in_progress,completed,failed'
        ];
    }
}
