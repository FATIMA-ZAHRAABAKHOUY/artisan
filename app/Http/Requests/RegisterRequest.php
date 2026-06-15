<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:client,artisan'],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialty' => ['required_if:role,artisan', 'nullable', 'string', 'max:100'],
            'city' => ['required_if:role,artisan', 'nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role.required' => 'Le type de compte est obligatoire.',
            'role.in' => 'Le type de compte sélectionné est invalide.',
            'specialty.required_if' => 'La spécialité est obligatoire pour les artisans.',
            'city.required_if' => 'La ville est obligatoire pour les artisans.',
        ];
    }
}
