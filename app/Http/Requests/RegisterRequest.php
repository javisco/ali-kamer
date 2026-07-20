<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name'     => 'required|string',
            'email'    => "required|email|unique:users,email",
            'phone'    => "required|string|max:15|unique:users,phone",
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:seller,buyer',

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'le nom est obligatoire',
            'name.string' => 'le nom doit etre une chaine de caracter',
            'email.required' => 'l\'email est obligatoire',
            'email.unique' => 'l\'email exite deja',
            'phone.required' => 'le numero de telephone est obligatoire',
            'phone.max' => 'le numero de telephone ne doit pas avoir plus de 15  caracters',
            'role.required' => 'veillez cochez un case entre vendeur et acheteur',
            'role.in' => 'choix invalide',
        ];
    }
}
