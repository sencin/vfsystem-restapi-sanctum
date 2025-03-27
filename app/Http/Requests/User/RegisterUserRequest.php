<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'extension_name' =>'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|max:255',
            'gender' => 'in:male,female',
            'id_card' => 'nullable|file|mimes:jpeg,png,jpg|max:10240',
            'password' => 'required|confirmed',
            'status'=>'nullable|in:active,inactive,pending,rejected',
            'role'=> 'nullable|in:superadmin,admin,staff,user,student,farmer,developer'

        ];
    }
    public function messages()
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.string' => 'First name must only contain letters.',
            'first_name.max' => 'First name must not exceed 255 characters.',

            'last_name.required' => 'Please enter your last name.',
            'last_name.string' => 'Last name must only contain letters.',
            'last_name.max' => 'Last name must not exceed 255 characters.',

            'extension_name.string' => 'Extension name must be a valid text.',
            'extension_name.max' => 'Extension name must not exceed 255 characters.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use. Please try a different one.',

            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a valid string.',
            'phone_number.max' => 'Phone number must not exceed 255 characters.',

            'gender.in' => 'Gender must be either Male or Female.',

            'id_card.file' => 'The uploaded file must be a valid document.',
            'id_card.mimes' => 'The ID card must be in JPEG, PNG, or JPG format.',
            'id_card.max' => 'The ID card file size must not exceed 10MB.',

            'password.required' => 'A password is required.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
