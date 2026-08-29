<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Documents obligatoires
            'cni_front_url' => ['required', 'file', 'image', 'max:5120'],
            'cni_back_url'  => ['required', 'file', 'image', 'max:5120'],
            'selfie_url'    => ['required', 'file', 'image', 'max:5120'],

            // RCCM optionnel
            'rccm_url'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

            // Numéro MoMo — peut être différent de celui saisi à l'inscription
            // C'est le numéro qui sera utilisé pour les retraits
            // 'momo_number'   => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            // 'momo_operator' => ['required', 'in:mtn,orange'],
        ];
    }

    public function messages(): array
    {
        return [
            'cni_front_url.required' => 'La photo recto de votre CNI est obligatoire.',
            'cni_front_url.image'    => 'Le recto CNI doit être une image (JPEG, PNG).',
            'cni_front_url.max'      => 'La photo ne doit pas dépasser 5 MB.',
            'cni_back_url.required'  => 'La photo verso de votre CNI est obligatoire.',
            'selfie_url.required'    => 'Le selfie avec votre CNI est obligatoire.',
            // 'momo_number.required'   => 'Le numéro Mobile Money de retrait est obligatoire.',
            // 'momo_number.regex'      => 'Le numéro MoMo doit commencer par 6 et contenir 9 chiffres.',
            // 'momo_operator.required' => 'Choisissez votre opérateur Mobile Money.',
        ];
    }
}