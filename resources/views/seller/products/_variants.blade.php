@php
    $variantBuilder = $variantBuilder ?? [
        'has_variants' => false,
        'presets' => config('product_attributes.presets', []),
        'max_attributes' => 3,
        'max_values' => 12,
        'max_total_values' => 30,
        'max_combinations' => 36,
        'max_active_variants' => 36,
        'attributes' => [],
        'variants' => [],
    ];

    if (old('has_variants') !== null) {
        $variantBuilder['has_variants'] = (bool) old('has_variants');
        $variantBuilder['attributes'] = collect(old('attributes', []))->map(function ($attr) {
            return [
                'name'   => $attr['name'] ?? '',
                'values' => array_values(array_filter($attr['values'] ?? [])),
                'images' => [],
            ];
        })->values()->all();
        $variantBuilder['variants'] = collect(old('variants', []))->map(function ($variant) {
            $values = array_values($variant['values'] ?? []);
            return [
                'id'        => $variant['id'] ?? null,
                'key'       => implode('||', $values),
                'label'     => implode(' / ', $values),
                'values'    => $values,
                'price'     => $variant['price'] ?? 0,
                'old_price' => $variant['old_price'] ?? '',
                'stock'     => $variant['stock'] ?? 0,
                'sku'       => $variant['sku'] ?? '',
                'is_active' => (bool) ($variant['is_active'] ?? true),
            ];
        })->values()->all();
    }
@endphp

<div class="space-y-4 rounded-2xl border border-gray-200 bg-[#F7F7F2]/40 p-4 sm:p-5">
    <div>
        <h2 class="text-sm font-black text-[#0a1b12]">Variantes</h2>
        <p class="mt-1 text-xs text-gray-500">
            Couleur, taille, stockage… L’acheteur choisit une combinaison vendable. Sans variante, le prix et le stock du produit suffisent.
        </p>
    </div>

    <input type="hidden" name="has_variants" :value="hasVariants ? 1 : 0">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-3 transition"
            :class="!hasVariants ? 'border-[#016837] bg-[#016837]/5' : 'border-gray-200'">
            <input type="radio" class="h-4 w-4 text-[#016837]" :checked="!hasVariants" @change="hasVariants = false">
            <span>
                <span class="block text-xs font-bold text-[#0a1b12]">Non — produit simple</span>
                <span class="block text-[11px] text-gray-500">Un seul prix et un seul stock</span>
            </span>
        </label>
        <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-3 transition"
            :class="hasVariants ? 'border-[#016837] bg-[#016837]/5' : 'border-gray-200'">
            <input type="radio" class="h-4 w-4 text-[#016837]" :checked="hasVariants" @change="enableVariants()">
            <span>
                <span class="block text-xs font-bold text-[#0a1b12]">Oui — plusieurs options</span>
                <span class="block text-[11px] text-gray-500">Chaque combinaison a son prix et son stock</span>
            </span>
        </label>
    </div>

    <div x-show="hasVariants" x-cloak class="space-y-4">
        <template x-for="(attr, attrIndex) in attributes" :key="attrIndex">
            <div class="rounded-xl border border-gray-200 bg-white p-3.5 space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <label class="text-xs font-bold text-[#0a1b12]">Attribut <span x-text="attrIndex + 1"></span></label>
                    <button type="button" class="text-[11px] font-bold text-[#E30613]" @click="removeAttribute(attrIndex)"
                        x-show="attributes.length > 1">Retirer</button>
                </div>

                <select class="w-full rounded-xl border border-gray-200 bg-[#F7F7F2]/60 px-3 py-2 text-xs font-semibold"
                    x-model="attr.name" @change="generateVariants()">
                    <option value="">— Choisir ou personnaliser —</option>
                    <template x-for="preset in presets" :key="preset">
                        <option :value="preset" x-text="preset"></option>
                    </template>
                    <option value="__custom">Personnalisé…</option>
                </select>
                <input type="text" x-show="attr.name === '__custom' || (attr.name && !presets.includes(attr.name))"
                    x-model="attr.customName" @input="attr.name = attr.customName; generateVariants()"
                    placeholder="Nom de l'attribut" maxlength="40"
                    class="w-full rounded-xl border border-gray-200 bg-[#F7F7F2]/60 px-3 py-2 text-xs font-semibold">

                <input type="hidden" :name="'attributes[' + attrIndex + '][name]'" :value="attributeName(attr)">

                <div>
                    <p class="mb-1.5 text-[11px] font-bold text-gray-500">Valeurs</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(value, valueIndex) in attr.values" :key="valueIndex">
                            <span class="inline-flex items-center gap-1 rounded-full bg-[#016837]/10 px-2.5 py-1 text-[11px] font-bold text-[#016837]">
                                <span x-text="value"></span>
                                <button type="button" @click="removeValue(attrIndex, valueIndex)">×</button>
                                <input type="hidden" :name="'attributes[' + attrIndex + '][values][]'" :value="value">
                            </span>
                        </template>
                    </div>
                    <div class="mt-2 flex gap-2">
                        <input type="text" x-model="attr.newValue" @keydown.enter.prevent="addValue(attrIndex)"
                            placeholder="+ Ajouter une valeur" maxlength="40"
                            class="flex-1 rounded-xl border border-gray-200 bg-[#F7F7F2]/60 px-3 py-2 text-xs font-semibold">
                        <button type="button" @click="addValue(attrIndex)"
                            class="rounded-xl bg-[#016837] px-3 py-2 text-[11px] font-bold text-white">Ajouter</button>
                    </div>
                    <p class="mt-1 text-[10px] text-gray-400">Photo optionnelle pour cette valeur (ex. couleur). Laissez vide pour conserver la photo actuelle.</p>
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <template x-for="(value, valueIndex) in attr.values" :key="'img-' + valueIndex">
                            <label class="block rounded-lg border border-dashed border-gray-200 p-2 text-center">
                                <span class="block truncate text-[10px] font-bold text-slate-600" x-text="value"></span>
                                <img x-show="attr.images && attr.images[valueIndex]" :src="attr.images[valueIndex]"
                                    class="mx-auto mt-1 h-10 w-10 rounded object-cover">
                                <input type="file" accept="image/*"
                                    :name="'value_images[' + attrIndex + '][' + valueIndex + ']'"
                                    class="mt-1 w-full text-[10px]">
                            </label>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <button type="button" @click="addAttribute()"
            x-show="attributes.length < maxAttributes"
            class="text-xs font-bold text-[#016837]">+ Ajouter un attribut</button>

        <p class="text-[11px] font-semibold text-slate-600">
            <span x-text="variants.length"></span> combinaison(s) générée(s)
            <span class="text-gray-400">(max <span x-text="maxCombinations"></span>)</span>
            <span class="ml-2 text-gray-400">• <span x-text="totalValues"></span>/<span x-text="maxTotalValues"></span> valeurs</span>
        </p>

        <div x-show="tooMany" class="rounded-xl border border-[#E30613]/20 bg-[#E30613]/10 p-3 text-xs font-semibold text-[#E30613]">
            La configuration dépasse une limite administrateur. Réduisez le nombre d'attributs, de valeurs ou de combinaisons.
        </div>

        <div x-show="variants.length" class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="min-w-full text-left text-[11px]">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-3 py-2 font-bold">Variante</th>
                        <th class="px-3 py-2 font-bold">Prix</th>
                        <th class="px-3 py-2 font-bold">Ancien prix</th>
                        <th class="px-3 py-2 font-bold">Stock</th>
                        <th class="px-3 py-2 font-bold">SKU</th>
                        <th class="px-3 py-2 font-bold">Vendable</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(variant, index) in variants" :key="variant.key">
                        <tr class="border-t border-slate-100" :class="!variant.is_active ? 'opacity-50' : ''">
                            <td class="px-3 py-2 font-bold text-slate-800">
                                <input type="hidden" :name="'variants[' + index + '][id]'" :value="variant.id || ''">
                                <span x-text="variant.label"></span>
                                <template x-for="(value, vIndex) in variant.values" :key="vIndex">
                                    <input type="hidden" :name="'variants[' + index + '][values][]'" :value="value">
                                </template>
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="1" required x-model.number="variant.price"
                                    :name="'variants[' + index + '][price]'"
                                    class="w-24 rounded-lg border border-gray-200 px-2 py-1 text-xs font-semibold">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="1" x-model="variant.old_price"
                                    :name="'variants[' + index + '][old_price]'"
                                    class="w-24 rounded-lg border border-gray-200 px-2 py-1 text-xs font-semibold">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="0" required x-model.number="variant.stock"
                                    :name="'variants[' + index + '][stock]'"
                                    class="w-20 rounded-lg border border-gray-200 px-2 py-1 text-xs font-semibold">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" maxlength="40" x-model="variant.sku"
                                    :name="'variants[' + index + '][sku]'"
                                    class="w-24 rounded-lg border border-gray-200 px-2 py-1 text-xs font-semibold">
                            </td>
                            <td class="px-3 py-2">
                                <input type="hidden" :name="'variants[' + index + '][is_active]'" :value="variant.is_active ? 1 : 0">
                                <input type="checkbox" :checked="variant.is_active" @change="variant.is_active = $event.target.checked">
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function productForm(initial) {
    return {
        activeImage: null,
        hasVariants: !!initial.has_variants,
        presets: initial.presets || [],
        maxAttributes: initial.max_attributes || 3,
        maxValues: initial.max_values || 12,
        maxTotalValues: initial.max_total_values || 30,
        maxCombinations: initial.max_combinations || 36,
        maxActiveVariants: initial.max_active_variants || 36,
        attributes: (initial.attributes || []).map(attr => ({
            name: attr.name,
            customName: attr.name,
            values: attr.values || [],
            images: attr.images || [],
            newValue: '',
        })),
        variants: initial.variants || [],
        tooMany: false,

        get totalValues() {
            return this.attributes.reduce((total, attr) => total + (attr.values || []).filter(Boolean).length, 0);
        },

        attributeName(attr) {
            if (attr.name === '__custom') return (attr.customName || '').trim();
            return (attr.name || '').trim();
        },

        enableVariants() {
            this.hasVariants = true;
            if (this.attributes.length === 0) {
                this.addAttribute();
            }
        },

        addAttribute() {
            if (this.attributes.length >= this.maxAttributes) return;
            this.attributes.push({ name: '', customName: '', values: [], images: [], newValue: '' });
        },

        removeAttribute(index) {
            this.attributes.splice(index, 1);
            this.generateVariants();
        },

        addValue(attrIndex) {
            const attr = this.attributes[attrIndex];
            const value = (attr.newValue || '').trim();
            if (!value || attr.values.length >= this.maxValues || this.totalValues >= this.maxTotalValues) return;
            if (attr.values.some(v => v.toLowerCase() === value.toLowerCase())) {
                attr.newValue = '';
                return;
            }
            attr.values.push(value);
            attr.images.push(null);
            attr.newValue = '';
            this.generateVariants();
        },

        removeValue(attrIndex, valueIndex) {
            this.attributes[attrIndex].values.splice(valueIndex, 1);
            this.attributes[attrIndex].images.splice(valueIndex, 1);
            this.generateVariants();
        },

        // Un input file vide ne doit pas être envoyé au serveur.
        // Cela évite d'envoyer un UploadedFile avec UPLOAD_ERR_NO_FILE.
        stripEmptyFileInputs(event) {
            event.currentTarget.querySelectorAll('input[type="file"][name]').forEach(input => {
                if (!input.files || input.files.length === 0) {
                    input.removeAttribute('name');
                }
            });
        },

        cartesian(sets) {
            return sets.reduce((acc, set) => acc.flatMap(prefix => set.map(value => [...prefix, value])), [[]]);
        },

        generateVariants() {
            const ready = this.attributes
                .map(attr => ({ name: this.attributeName(attr), values: attr.values.filter(Boolean) }))
                .filter(attr => attr.name && attr.values.length);

            if (!this.hasVariants || ready.length === 0) {
                this.variants = [];
                this.tooMany = false;
                return;
            }

            const combinationCount = ready.reduce((total, attr) => total * attr.values.length, 1);
            this.tooMany = combinationCount > this.maxCombinations
                || this.totalValues > this.maxTotalValues
                || combinationCount > this.maxActiveVariants;
            const combos = this.tooMany ? [] : this.cartesian(ready.map(attr => attr.values));
            if (this.tooMany) {
                this.variants = [];
                return;
            }

            const previous = {};
            (this.variants || []).forEach(variant => { previous[variant.key] = variant; });

            const defaultPrice = Number(document.querySelector('input[name="price"]')?.value || 0);

            this.variants = combos.map(values => {
                const key = values.join('||');
                const existing = previous[key] || {};
                return {
                    id: existing.id || null,
                    key,
                    label: values.join(' / '),
                    values,
                    price: existing.price ?? defaultPrice,
                    old_price: existing.old_price ?? '',
                    stock: existing.stock ?? 0,
                    sku: existing.sku ?? '',
                    is_active: existing.is_active !== undefined ? !!existing.is_active : true,
                };
            });
        },
    };
}
</script>
@endpush
@endonce