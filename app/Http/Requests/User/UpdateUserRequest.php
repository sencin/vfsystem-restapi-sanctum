<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class UpdateUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'extension_name' =>'nullable|string|max:255',
            'role'=> 'required|in:superadmin,admin,staff,user,student,farmer,developer',
            'status' => 'required|in:active,inactive,pending,rejected',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.string' => 'The first name must be a valid string.',
            'first_name.max' => 'The first name must not exceed 255 characters.',

            'last_name.required' => 'Please enter your last name.',
            'last_name.string' => 'The last name must be a valid string.',
            'last_name.max' => 'The last name must not exceed 255 characters.',

            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email address.',

            'extension_name.string' => 'The extension name must be a valid string.',
            'extension_name.max' => 'The extension name must not exceed 255 characters.',

            'password.required' => 'Please enter a password.',
            'password.confirmed' => 'The password confirmation does not match.',

            'role.required' => 'Please select a role.',
            'role.in' => 'The selected role is invalid.',

            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid. Please choose a valid status.',
        ];
    }
}
