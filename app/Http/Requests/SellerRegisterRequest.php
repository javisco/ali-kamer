<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SellerRegisterRequest extends FormRequest
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

            // Infos personnelles
            'name'          => ['required', 'string', 'min:2', 'max:100'],

            // phone_momo = OBLIGATOIRE (numéro de retrait des gains)
            // C'est le champ principal du vendeur
            'phone_momo'    => ['required', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone_momo'],

            // Opérateur MoMo obligatoire
            'momo_operator' => ['required', 'in:mtn,orange'],

            // phone = OPTIONNEL (contact)
            'phone'         => ['nullable', 'string', 'regex:/^6[0-9]{8}$/', 'unique:users,phone'],

            'email'         => ['required', 'email', 'max:191', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],

            // Infos boutique — créée en même temps que le compte
            'shop_name'     => ['required', 'string', 'min:3', 'max:100'],
            'city'          => ['required', 'string'],
            'category'      => ['required', 'string'],
            'description'   => ['nullable', 'string', 'max:500'],

        ];
    }
    public function messages()
    {
        return [

            'phone_momo.required' => 'Le numéro Mobile Money est obligatoire.',
            'phone_momo.regex'    => 'Le numéro MoMo doit commencer par 6 et contenir 9 chiffres.',
            'phone_momo.unique'   => 'Ce numéro MoMo est déjà utilisé.',
            'momo_operator.required' => 'Choisissez votre opérateur Mobile Money.',

        ];
    }
}
