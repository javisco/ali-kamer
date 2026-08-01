<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id'            => ['required', 'exists:categories,id'],
            'title'                  => ['required', 'string', 'min:10', 'max:80'],
            'description'            => ['required', 'string', 'min:50'],
            'price'                  => ['required', 'integer', 'min:20', 'max:10000000'],
            'old_price'              => ['nullable', 'integer', 'gt:price'],
            'stock'                  => ['required', 'integer', 'min:0'],
            'min_quantity'           => ['required', 'integer', 'min:1'],
            'shipping_included'      => ['required', 'boolean'],
            'shipping_threshold_qty' => ['nullable', 'integer', 'min:1'],
            'specifications'         => ['nullable', 'array'],
            'images'                 => ['required', 'array', 'min:1', 'max:5'],
            'images.*'               => ['image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'          => 'Le titre doit faire au moins 10 caractères.',
            'title.max'          => 'Le titre ne doit pas dépasser 80 caractères.',
            'description.min'    => 'La description doit faire au moins 50 caractères.',
            'price.min'          => 'Le prix minimum est 100 FCFA.',
            'price.max'          => 'Le prix maximum est 10 000 000 FCFA.',
            'old_price.gt'       => 'L\'ancien prix doit être supérieur au prix actuel.',
            'images.required'    => 'Ajoutez au moins une photo.',
            'images.max'         => 'Maximum 5 photos par produit.',
            'images.*.max'       => 'Chaque photo ne doit pas dépasser 2 MB.',
            'images.*.image'     => 'Les fichiers doivent être des images.',
            'min_quantity.min'   => 'La quantité minimale est 1.',
            'category_id.exists' => 'Catégorie invalide.',
        ];
    }
}