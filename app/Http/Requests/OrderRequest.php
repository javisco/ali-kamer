<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'product_id'       => ['required', 'exists:products,id'],
            'variant_id'       => ['nullable', 'exists:product_variants,id'],
            'quantity'         => ['required', 'integer', 'min:1'],
            'destination_city' => ['required', 'string'],
            'payer_phone'      => ['required', 'string', 'regex:/^6[0-9]{8}$/'],
            'payer_operator'   => ['required', 'in:mtn,orange'],
            'note'             => ['nullable', 'string', 'max:500'],
        ];
    }
}
