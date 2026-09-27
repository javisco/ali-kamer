<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Validator;
use App\Models\PlatformSetting;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        $hasVariants = $this->boolean('has_variants');

        return [
            'category_id'            => ['required', 'exists:categories,id'],
            'title'                  => ['required', 'string', 'max:80'],
            'description'            => ['required', 'string'],
            'price'                  => ['required', 'integer', 'min:1', 'max:' . (int) PlatformSetting::getValue('max_product_price', 10000000)],
            'old_price'              => ['nullable', 'integer', 'gt:price'],
            'stock'                  => [$hasVariants ? 'nullable' : 'required', 'integer', 'min:0'],
            'min_quantity'           => ['required', 'integer', 'min:1'],
            'shipping_included'      => ['required', 'boolean'],
            'shipping_threshold_qty' => ['nullable', 'integer', 'min:1'],
            'specifications'         => ['nullable', 'array'],
            'images'                 => [$this->isMethod('post') ? 'required' : 'nullable', 'array', 'min:1', 'max:5'],
            'images.*'               => ['image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'has_variants'           => ['nullable', 'boolean'],
            'attributes'             => [$hasVariants ? 'required' : 'nullable', 'array', 'min:1', 'max:' . (int) PlatformSetting::getValue('variant_max_attributes', 3)],
            'attributes.*.name'      => [$hasVariants ? 'required' : 'nullable', 'string', 'max:40'],
            'attributes.*.values'    => [$hasVariants ? 'required' : 'nullable', 'array', 'min:1', 'max:' . (int) PlatformSetting::getValue('variant_max_values_per_attribute', 12)],
            'attributes.*.values.*'  => ['nullable', 'string', 'max:40'],
            'variants'               => [$hasVariants ? 'required' : 'nullable', 'array'],
            'variants.*.id'          => ['nullable', 'integer'],
            'variants.*.values'      => ['required_if:has_variants,true', 'array', 'min:1'],
            'variants.*.price'       => [$hasVariants ? 'required' : 'nullable', 'integer', 'min:1', 'max:' . (int) PlatformSetting::getValue('max_product_price', 10000000)],
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

            $attributes = $this->input('attributes', []);
            $totalValues = 0;
            foreach ($attributes as $attribute) {
                $totalValues += count($attribute['values'] ?? []);
            }

            $maxTotalValues = (int) PlatformSetting::getValue('variant_max_total_values', 30);
            if ($totalValues > $maxTotalValues) {
                $validator->errors()->add('attributes', "Maximum {$maxTotalValues} valeurs au total pour un produit.");
            }

            $maxCombinations = (int) PlatformSetting::getValue('variant_max_combinations', 36);
            $combinationCount = 1;
            foreach ($attributes as $attribute) {
                $combinationCount *= max(1, count($attribute['values'] ?? []));
            }
            if ($combinationCount > $maxCombinations) {
                $validator->errors()->add('variants', "Maximum {$maxCombinations} combinaisons autorisées.");
            }

            $maxActive = (int) PlatformSetting::getValue('variant_max_active', 36);
            $activeCount = collect($this->input('variants', []))->filter(fn ($v) => filter_var($v['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN))->count();
            if ($activeCount > $maxActive) {
                $validator->errors()->add('variants', "Maximum {$maxActive} variantes actives autorisées.");
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
        // Un champ <input type="file"> laissé vide peut être envoyé par PHP
        // comme un UploadedFile avec UPLOAD_ERR_NO_FILE au lieu de null.
        // Laravel applique alors la règle `image` sur cet objet invalide et
        // rejette toute la requête, même si le champ est `nullable`.
        // On retire uniquement ces entrées vides. Les vraies erreurs d'upload
        // (taille dépassée, erreur serveur, etc.) restent présentes afin que
        // la validation puisse correctement les signaler.
        $this->cleanEmptyUploadedFiles('images');
        $this->cleanEmptyUploadedFiles('value_images');

        $this->merge([
            'has_variants' => $this->boolean('has_variants'),
            'stock'        => $this->boolean('has_variants')
                ? ($this->input('stock') ?: 0)
                : $this->input('stock'),
        ]);
    }

    /**
     * Supprime uniquement les UploadedFile correspondant à UPLOAD_ERR_NO_FILE.
     * Les autres erreurs d'upload sont conservées pour être validées par Laravel.
     */
    private function cleanEmptyUploadedFiles(string $key): void
    {
        $files = $this->file($key);

        if ($files === null) {
            return;
        }

        $cleaned = $this->removeNoFileEntries($files);

        if ($cleaned === null || $cleaned === []) {
            $this->files->remove($key);
            return;
        }

        $this->files->set($key, $cleaned);
    }

    private function removeNoFileEntries(mixed $value): mixed
    {
        if ($value instanceof UploadedFile) {
            return $value->getError() === UPLOAD_ERR_NO_FILE ? null : $value;
        }

        if (! is_array($value)) {
            return $value;
        }

        $result = [];

        foreach ($value as $key => $item) {
            $cleaned = $this->removeNoFileEntries($item);

            if ($cleaned !== null) {
                $result[$key] = $cleaned;
            }
        }

        return $result;
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