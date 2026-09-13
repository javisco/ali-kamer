<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        $hasVariants = $this->boolean('has_variants');

        return [
            'category_id'            => ['required', 'exists:categories,id'],
            'title'                  => ['required', 'string', 'max:80'],
            'description'            => ['required', 'string'],
            'price'                  => ['required', 'integer', 'min:1', 'max:10000000'],
            'old_price'              => ['nullable', 'integer', 'gt:price'],
            'stock'                  => [$hasVariants ? 'nullable' : 'required', 'integer', 'min:0'],
            'min_quantity'           => ['required', 'integer', 'min:1'],
            'shipping_included'      => ['required', 'boolean'],
            'shipping_threshold_qty' => ['nullable', 'integer', 'min:1'],
            'specifications'         => ['nullable', 'array'],
            'images'                 => [$this->isMethod('post') ? 'required' : 'nullable', 'array', 'min:1', 'max:5'],
            'images.*'               => ['image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'has_variants'           => ['nullable', 'boolean'],
            'attributes'             => [$hasVariants ? 'required' : 'nullable', 'array', 'min:1', 'max:' . config('product_attributes.max_attributes', 3)],
            'attributes.*.name'      => [$hasVariants ? 'required' : 'nullable', 'string', 'max:40'],
            'attributes.*.values'    => [$hasVariants ? 'required' : 'nullable', 'array', 'min:1', 'max:' . config('product_attributes.max_values', 12)],
            'attributes.*.values.*'  => ['nullable', 'string', 'max:40'],
            'variants'               => [$hasVariants ? 'required' : 'nullable', 'array'],
            'variants.*.values'      => ['nullable', 'array'],
            'variants.*.price'       => [$hasVariants ? 'required' : 'nullable', 'integer', 'min:1', 'max:10000000'],
            'variants.*.old_price'   => ['nullable', 'integer', 'min:1'],
            'variants.*.stock'       => [$hasVariants ? 'required' : 'nullable', 'integer', 'min:0'],
            'variants.*.sku'         => ['nullable', 'string', 'max:40'],
            'variants.*.is_active'   => ['nullable'],
            'value_images'           => ['nullable', 'array'],
            'value_images.*'         => ['nullable', 'array'],
            'value_images.*.*'       => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->boolean('has_variants')) {
                return;
            }

            foreach ($this->input('variants', []) as $index => $variant) {
                $old = $variant['old_price'] ?? null;
                $price = $variant['price'] ?? null;
                if ($old !== null && $old !== '' && $price && (int) $old <= (int) $price) {
                    $validator->errors()->add(
                        "variants.$index.old_price",
                        "L'ancien prix de la variante doit être supérieur au prix actuel."
                    );
                }
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_variants' => $this->boolean('has_variants'),
            'stock'        => $this->boolean('has_variants')
                ? ($this->input('stock') ?: 0)
                : $this->input('stock'),
        ]);
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
            'attributes.required' => 'Ajoutez au moins un attribut (couleur, taille…).',
            'variants.required'  => 'Générez le tableau des variantes avant d\'enregistrer.',
        ];
    }
}
