<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();

        return [
            'name' => ['string', 'max:255'],
            'username' => ['string', 'max:20', 'unique:users,username,' . $user->id],
            'email' => ['string', 'email', 'max:255', 'unique:users,email,' . $user->id]
        ];
    }
}
