<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'username' => ['required', 'string', 'unique:users,username', 'max:25'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8', 'max:25', 'confirmed']
        ];
    }
}
