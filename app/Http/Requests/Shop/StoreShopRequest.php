<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'min:3', 'max:60'],
            'city'        => ['required', 'string', 'max:50'],
            'phone'       => ['required', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'logo'        => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Le nom de la boutique doit faire au moins 3 caractères.',
        ];
    }
}