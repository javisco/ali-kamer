<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class KycRequest extends FormRequest
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
            'cni_front_url'   => ['required', 'image', 'max:5120'],
            'cni_back_url'    => ['required', 'image', 'max:5120'],
            'selfie_url'      => ['required', 'image', 'max:5120'],
            'rccm_url'        => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],

        ];
    }

    public function messages(): array
    {
        return [
            'cni_front_url.required' => 'La photo recto de votre CNI est obligatoire.',
            'cni_back_url.required'  => 'La photo verso de votre CNI est obligatoire.',
            'selfie_url.required'    => 'Le selfie avec votre CNI est obligatoire.',
            'cni_front_url.max'      => 'Chaque document ne doit pas dépasser 5 MB.',
            'momo_number_url.regex'  => 'Numéro MoMo invalide. Exemple : 655123456',
            'cni_back_url.max' => 'Chaque document ne doit pas dépasser 5 MB.',
            'selfie_url.max' => 'Chaque document ne doit pas dépasser 5 MB.',

        ];
    }
}
