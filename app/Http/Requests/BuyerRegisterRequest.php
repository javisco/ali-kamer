<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuyerRegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [

            'name'                  => ['required', 'string', 'min:2', 'max:100'],
            'phone_momo'                 => ['required', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone'],
            'email'                 => ['required', 'email', 'max:191', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],


        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'le nom est requis',
            'phone_momo.regex'    => 'Le numéro doit commencer par 6 et contenir 9 chiffres.',
            'phone_momo.unique'   => 'Ce numéro est déjà utilisé.',
            'email.unique'   => 'Cet email est déjà utilisé.',
            'password.min'   => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',

        ];
    }
}
