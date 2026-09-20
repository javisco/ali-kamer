@extends('base')

@section('title', $product->title)

@section('content')

<script>
    function productShow() {
        return {
            lightboxOpen: false,
            lightboxImg: @json($product->images->first() ? Storage::url($product->images->first()->url) : ''),
            defaultImg: @json($product->images->first() ? Storage::url($product->images->first()->url) : ''),
            quantity: {{ (int) ($product->min_quantity ?? 1) }},
            minQty: {{ (int) ($product->min_quantity ?? 1) }},
            maxStock: {{ (int) $product->availableStock() }},
            copied: false,
            variants: @json($variantsData ?? []),
            selectedValues: {},
            selectedVariant: null,

            shareUrl() {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(window.location.href);
                    this.copied = true;
                    setTimeout(() => {
                        this.copied = false;
                    }, 2000);
                }
            },

            selectValue(attrId, valueId, imageUrl) {
                if (!this.isValueAvailable(attrId, valueId)) return;
                this.selectedValues[attrId] = valueId;
                if (imageUrl) {
                    this.lightboxImg = imageUrl;
                }
                this.updateVariant();
            },

            isValueAvailable(attrId, valueId) {
                const trial = { ...this.selectedValues, [attrId]: valueId };
                const selected = Object.values(trial).map(Number);
                return this.variants.some(v =>
                    selected.every(id => v.value_ids.map(Number).includes(id)) && v.stock > 0
                );
            },

            updateVariant() {
                const selected = Object.values(this.selectedValues).map(Number).sort((a, b) => a - b);
                const attrCount = Object.keys(this.selectedValues).length;
                const needed = {{ (int) $product->attributes->count() }};

                this.selectedVariant = (attrCount === needed)
                    ? this.variants.find(v => {
                        const ids = [...v.value_ids].map(Number).sort((a, b) => a - b);
                        return JSON.stringify(ids) === JSON.stringify(selected);
                    }) ?? null
                    : null;

                if (this.selectedVariant) {
                    this.maxStock = this.selectedVariant.stock;
                    if (this.quantity > this.maxStock) {
                        this.quantity = Math.max(this.minQty, this.maxStock);
                    }
                    if (this.selectedVariant.images && this.selectedVariant.images.length) {
                        this.lightboxImg = this.selectedVariant.images[0];
                    }
                }
            },

            formatPrice(price) {
                if (price === null || price === undefined || isNaN(price)) return '';
                return new Intl.NumberFormat('fr-FR').format(price);
            }
        };
    }
</script>

<div
    class="min-h-screen bg-[#F7F9F7] py-5 sm:py-7 pb-24 lg:pb-8"
    x-data="productShow()"
>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- =========================================================
            FIL D'ARIANE
        ========================================================== --}}
        <nav class="mb-5 flex items-center gap-2 overflow-x-auto whitespace-nowrap py-1 text-[10px] sm:text-[11px]">

            <a
                href="{{ route('buyer.home') }}"
                class="font-semibold text-slate-400 transition hover:text-[#016837]"
            >
                Accueil
            </a>

            <span class="text-slate-300">/</span>

            <a
                href="#"
                class="font-semibold text-slate-400 transition hover:text-[#016837]"
            >
                {{ $product->category->name }}
            </a>

            <span class="text-slate-300">/</span>

            <span class="truncate font-bold text-slate-700">
                {{ $product->title }}
            </span>

        </nav>


        {{-- =========================================================
            PRODUIT
        ========================================================== --}}
        <div class="mb-8 grid grid-cols-1 items-start gap-5 lg:grid-cols-12 lg:gap-6">


            {{-- =====================================================
                GALERIE
            ====================================================== --}}
            <div class="space-y-2.5 lg:col-span-6">

                <div
                    class="group relative flex h-[340px] w-full items-center justify-center
                           overflow-hidden rounded-2xl border border-slate-200 bg-white p-3
                           shadow-sm sm:h-[430px]"
                >

                    @if ($product->images->first())

                        <div
                            @click="lightboxOpen = true"
                            class="relative flex h-full w-full cursor-zoom-in items-center justify-center"
                        >

                            <img
                                :src="lightboxImg"
                                src="{{ Storage::url($product->images->first()->url) }}"
                                alt="{{ $product->title }}"
                                id="mainImage"
                                class="max-h-full max-w-full rounded-xl object-contain
                                       transition-transform duration-500 group-hover:scale-[1.02]"
                            >

                            {{-- Plein écran --}}
                            <span
                                class="absolute bottom-3 right-3 flex items-center gap-1.5
                                       rounded-lg bg-[#0A1B12]/85 px-2.5 py-1.5 text-[9px]
                                       font-bold text-white opacity-70 shadow-md backdrop-blur-md
                                       transition group-hover:opacity-100"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"
                                    />
                                </svg>

                                Agrandir
                            </span>

                        </div>

                    @else

                        <div class="flex h-full w-full flex-col items-center justify-center rounded-xl bg-slate-50">

                            <svg class="h-14 w-14 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>

                            <span class="mt-2 text-[10px] font-medium text-slate-400">
                                Aucune image disponible
                            </span>

                        </div>

                    @endif


                    {{-- =================================================
                        BADGES
                    ================================================== --}}
                    <div class="pointer-events-none absolute left-3 top-3 z-10 flex flex-col gap-1.5">

                        @if ($product->shipping_included)

                            <span
                                class="rounded-md bg-[#016837]/95 px-2.5 py-1 text-[9px]
                                       font-extrabold uppercase tracking-wide text-white shadow-sm"
                            >
                                Transport inclus
                            </span>

                        @endif

                        @if ($product->hasDiscount())

                            <span
                                class="w-fit rounded-md bg-[#E30613] px-2.5 py-1 text-[9px]
                                       font-extrabold text-white shadow-sm"
                            >
                                -{{ $product->discountPercent() }}%
                            </span>

                        @endif

                    </div>


                    {{-- =================================================
                        ACTIONS FLOTTANTES
                    ================================================== --}}
                    <div class="absolute right-3 top-3 z-20 flex items-center gap-1.5">

                        @auth

                            <form
                                method="POST"
                                action="{{ route('buyer.wishlist.toggle', $product->id) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    title="Ajouter aux favoris"
                                    class="flex h-8 w-8 items-center justify-center rounded-full
                                           border border-slate-200 bg-white/95 shadow-sm
                                           backdrop-blur transition hover:scale-105
                                           hover:border-[#E30613]/30"
                                >

                                    <svg
                                        class="h-4 w-4 {{ $isWishlisted
                                            ? 'fill-[#E30613] text-[#E30613]'
                                            : 'text-slate-500 hover:text-[#E30613]' }}"
                                        fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                        />
                                    </svg>

                                </button>
                            </form>

                        @endauth


                        @if (!$product->hasVariants())

                            <form
                                method="POST"
                                action="{{ route('buyer.cart.add', $product) }}"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="quantity"
                                    :value="quantity"
                                >

                                <button
                                    type="submit"
                                    title="Ajouter au panier"
                                    class="flex h-8 w-8 items-center justify-center rounded-full
                                           bg-[#016837] text-white shadow-sm transition
                                           hover:scale-105 hover:bg-[#01582f] active:scale-95"
                                >

                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>

                                </button>

                            </form>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    MINIATURES
                ================================================== --}}
                @if ($product->images->count() > 1)

                    <div class="flex gap-2 overflow-x-auto pb-1">

                        @foreach ($product->images as $index => $image)

                            <button
                                type="button"
                                @click="lightboxImg = '{{ Storage::url($image->url) }}'"
                                onclick="changeMainImage('{{ Storage::url($image->url) }}', this)"
                                class="thumb-btn flex h-14 w-14 shrink-0 items-center justify-center
                                       overflow-hidden rounded-xl border-2 bg-white p-1
                                       transition-all duration-200
                                       {{ $loop->first
                                           ? 'border-[#016837] shadow-sm'
                                           : 'border-slate-200 hover:border-[#016837]/50' }}"
                            >

                                <img
                                    src="{{ Storage::url($image->url) }}"
                                    alt=""
                                    class="h-full w-full rounded-lg object-contain"
                                >

                            </button>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- =====================================================
                BLOC ACHAT
            ====================================================== --}}
            <div class="space-y-3 lg:col-span-6">

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">

                    {{-- =================================================
                        CATÉGORIE + TITRE
                    ================================================== --}}
                    <div>

                        <span
                            class="inline-flex rounded-md bg-[#016837]/10 px-2 py-1
                                   text-[9px] font-extrabold uppercase tracking-wider text-[#016837]"
                        >
                            {{ $product->category->name }}
                        </span>

                        <h1 class="mt-2 text-lg font-extrabold leading-snug text-slate-900 sm:text-xl">
                            {{ $product->title }}
                        </h1>


                        {{-- Avis --}}
                        <div class="mt-1.5 flex items-center gap-2">

                            <div class="flex text-sm text-[#F9A01B]">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($productRating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>

                            <a
                                href="#reviews-section"
                                class="text-[10px] font-semibold text-slate-500 transition hover:text-[#016837]"
                            >
                                {{ number_format($productRating, 1) }}
                                ({{ $reviews->count() }} avis)
                            </a>

                        </div>

                    </div>


                    {{-- =================================================
                        PRIX + QUANTITÉ
                    ================================================== --}}
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3
                                rounded-xl border border-slate-100 bg-[#F7F9F7] p-3">

                        <div class="flex flex-wrap items-baseline gap-2">

                            <span class="text-2xl font-black text-[#016837]">
                                <span x-text="formatPrice(selectedVariant ? selectedVariant.price : {{ $product->minPrice() ?: $product->price }})">{{ number_format($product->minPrice() ?: $product->price, 0, ',', ' ') }}</span>
                                <span class="text-[10px] font-extrabold">
                                    FCFA
                                </span>
                            </span>

                            @if ($product->hasVariants() && $product->minPrice() !== $product->maxPrice())
                                <span class="text-[11px] font-semibold text-slate-500" x-show="!selectedVariant">
                                    à partir de
                                </span>
                            @endif

                            <span x-show="selectedVariant && selectedVariant.old_price && selectedVariant.old_price > selectedVariant.price"
                                class="text-[11px] font-semibold text-[#E30613] line-through"
                                x-text="selectedVariant ? formatPrice(selectedVariant.old_price) + ' FCFA' : ''">
                            </span>

                            @if (!$product->hasVariants() && $product->hasDiscount())

                                <span class="text-[11px] font-semibold text-[#E30613] line-through">
                                    {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                </span>

                            @endif

                        </div>


                        {{-- Quantité --}}
                        @if ($product->availableStock() > 0)

                            <div class="flex items-center rounded-lg border border-slate-200 bg-white p-1 shadow-sm">

                                <button
                                    type="button"
                                    @click="if (quantity > minQty) quantity--"
                                    :disabled="quantity <= minQty"
                                    class="flex h-7 w-7 items-center justify-center rounded-md
                                           text-sm font-bold text-slate-600 transition
                                           hover:bg-slate-100 disabled:cursor-not-allowed
                                           disabled:opacity-30"
                                >
                                    −
                                </button>

                                <span
                                    class="w-7 text-center text-xs font-extrabold text-slate-800"
                                    x-text="quantity"
                                ></span>

                                <button
                                    type="button"
                                    @click="if (quantity < maxStock) quantity++"
                                    :disabled="quantity >= maxStock"
                                    class="flex h-7 w-7 items-center justify-center rounded-md
                                           text-sm font-bold text-slate-600 transition
                                           hover:bg-slate-100 disabled:cursor-not-allowed
                                           disabled:opacity-30"
                                >
                                    +
                                </button>

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                        VARIANTES
                    ================================================== --}}
                    @if ($product->hasVariants())

                        <div id="variants-section" class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3">

                            @foreach ($product->attributes as $attribute)

                                <div class="mb-3 last:mb-0">

                                    <p class="mb-1.5 text-[10px] font-bold text-slate-700">
                                        {{ $attribute->name }}
                                    </p>

                                    <div class="flex flex-wrap gap-1.5">

                                        @foreach ($attribute->values as $value)

                                            <button
                                                type="button"
                                                @click="selectValue({{ $attribute->id }}, {{ $value->id }}, '{{ $value->image_path ? \Illuminate\Support\Facades\Storage::url($value->image_path) : '' }}')"
                                                :disabled="!isValueAvailable({{ $attribute->id }}, {{ $value->id }})"
                                                :class="selectedValues[{{ $attribute->id }}] === {{ $value->id }}
                                                    ? 'border-[#016837] bg-[#016837]/10 text-[#016837] font-bold'
                                                    : (!isValueAvailable({{ $attribute->id }}, {{ $value->id }})
                                                        ? 'border-slate-200 bg-slate-100 text-slate-300 line-through cursor-not-allowed'
                                                        : 'border-slate-300 bg-white text-slate-600 hover:border-[#016837]/50')"
                                                class="rounded-lg border px-2.5 py-1.5 text-[10px] transition"
                                            >
                                                {{ $value->value }}
                                            </button>

                                        @endforeach

                                    </div>

                                </div>

                            @endforeach


                            {{-- Variante sélectionnée --}}
                            <div
                                x-show="selectedVariant"
                                x-transition
                                class="mb-2 rounded-lg border border-[#016837]/10 bg-[#016837]/5 p-2"
                            >

                                <p
                                    class="text-sm font-extrabold text-[#016837]"
                                    x-text="selectedVariant ? formatPrice(selectedVariant.price) + ' FCFA' : ''"
                                ></p>

                                <p
                                    class="mt-0.5 text-[9px] text-slate-500"
                                    x-text="selectedVariant ? selectedVariant.stock + ' en stock' : ''"
                                ></p>

                            </div>


                            <form
                                method="POST"
                                action="{{ route('buyer.cart.add', $product) }}"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="quantity"
                                    :value="quantity"
                                >

                                <input
                                    type="hidden"
                                    name="variant_id"
                                    :value="selectedVariant?.id"
                                >

                                <button
                                    type="submit"
                                    :disabled="!selectedVariant || selectedVariant.stock === 0"
                                    class="w-full rounded-xl bg-[#016837] py-2.5 text-xs
                                           font-extrabold text-white shadow-sm
                                           shadow-[#016837]/20 transition hover:bg-[#01582f]
                                           disabled:cursor-not-allowed disabled:bg-slate-300
                                           disabled:shadow-none"
                                >

                                    <span x-show="!selectedVariant">
                                        Choisissez une variante
                                    </span>

                                    <span
                                        x-show="selectedVariant && selectedVariant.stock > 0"
                                        class="flex items-center justify-center gap-2"
                                    >
                                        <span>Ajouter au panier</span>

                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                    </span>

                                    <span x-show="selectedVariant && selectedVariant.stock === 0">
                                        Rupture de stock
                                    </span>

                                </button>

                            </form>

                        </div>

                    @else

                        {{-- Ajouter au panier --}}
                        <form
                            method="POST"
                            action="{{ route('buyer.cart.add', $product) }}"
                            class="mt-3"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="quantity"
                                :value="quantity"
                            >

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-xl
                                       bg-[#016837] py-2.5 text-xs font-extrabold text-white
                                       shadow-sm shadow-[#016837]/20 transition
                                       hover:bg-[#01582f] active:scale-[0.99]"
                            >

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>

                                Ajouter au panier

                            </button>

                        </form>

                    @endif


                    {{-- =================================================
                        STOCK + MINIMUM
                    ================================================== --}}
                    <div class="mt-3 grid grid-cols-2 gap-2">

                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-2.5">

                            <span class="block text-[8px] font-semibold uppercase tracking-wide text-slate-400">
                                Disponibilité
                            </span>

                            <span
                                class="mt-0.5 block text-[10px] font-extrabold
                                    {{ $product->availableStock() > 0 ? 'text-[#016837]' : 'text-[#E30613]' }}"
                            >
                                {{ $product->availableStock() > 0
                                    ? $product->availableStock() . ' en stock'
                                    : 'Rupture de stock' }}
                            </span>

                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-2.5">

                            <span class="block text-[8px] font-semibold uppercase tracking-wide text-slate-400">
                                Commande minimale
                            </span>

                            <span class="mt-0.5 block text-[10px] font-extrabold text-slate-800">
                                {{ $product->min_quantity ?? 1 }} unité(s)
                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                        BOUTIQUE
                    ================================================== --}}
                    <a
                        href="{{ route('shop.show', $product->shop) }}"
                        class="group mt-3 flex items-center gap-2.5 rounded-xl border border-slate-200
                               bg-slate-50 p-2.5 transition hover:border-[#016837]/30 hover:bg-white"
                    >

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg
                                   bg-[#016837] text-[10px] font-extrabold uppercase text-white shadow-sm"
                        >
                            {{ substr($product->shop->name, 0, 2) }}
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="truncate text-[10px] font-extrabold text-slate-900 transition group-hover:text-[#016837]">
                                {{ $product->shop->name }}
                            </p>

                            <p class="mt-0.5 flex items-center gap-1 text-[9px] text-slate-400">

                                <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>

                                <span class="truncate">
                                    {{ $product->shop->city ?? 'Localisation non renseignée' }}
                                </span>

                            </p>

                        </div>

                        <svg
                            class="h-3.5 w-3.5 text-slate-300 transition
                                   group-hover:translate-x-0.5 group-hover:text-[#016837]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>

                    </a>


                    {{-- =================================================
                        COMMANDER / CONTACTER
                    ================================================== --}}
                    <div class="mt-3 grid grid-cols-2 gap-2">

                        @auth

                            @if ($product->availableStock() > 0)

                                @if ($product->hasVariants())
                                    <a
                                        :href="selectedVariant ? '{{ route('buyer.orders.create', $product->id) }}?variant_id=' + selectedVariant.id : '#variants-section'"
                                        @click="if(!selectedVariant) { $event.preventDefault(); document.getElementById('variants-section')?.scrollIntoView({behavior: 'smooth'}); alert('Veuillez sélectionner les options du produit avant de commander.'); }"
                                        :class="selectedVariant ? 'bg-[#E30613] hover:bg-[#c90511]' : 'bg-slate-300 cursor-pointer'"
                                        class="flex items-center justify-center gap-1.5 rounded-xl
                                               px-3 py-2.5 text-[10px] font-extrabold
                                               text-white shadow-sm transition active:scale-[0.98]"
                                    >
                                        <span x-text="selectedVariant ? 'Commander' : 'Choisir une option'">Commander</span>
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
                                        </svg>
                                    </a>
                                @else
                                    <a
                                        href="{{ route('buyer.orders.create', $product->id) }}"
                                        class="flex items-center justify-center gap-1.5 rounded-xl
                                               bg-[#E30613] px-3 py-2.5 text-[10px] font-extrabold
                                               text-white shadow-sm shadow-[#E30613]/15 transition
                                               hover:bg-[#c90511] active:scale-[0.98]"
                                    >
                                        Commander
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
                                        </svg>
                                    </a>
                                @endif

                            @else

                                <button
                                    disabled
                                    class="cursor-not-allowed rounded-xl bg-slate-100 px-3 py-2.5
                                           text-[10px] font-extrabold text-slate-400"
                                >
                                    Rupture
                                </button>

                            @endif


                            <a
                                href="{{ route('messaging.start', [
                                    'product' => $product->id,
                                    'shop' => $product->shop->id
                                ]) }}"
                                class="flex items-center justify-center gap-1.5 rounded-xl border
                                       border-slate-200 bg-white px-3 py-2.5 text-[10px]
                                       font-bold text-slate-700 transition hover:border-[#016837]/30
                                       hover:bg-[#016837]/5 hover:text-[#016837]"
                            >

                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    />
                                </svg>

                                Contacter

                            </a>

                        @else

                            <a
                                href="{{ route('login') }}"
                                class="col-span-2 rounded-xl bg-[#016837] px-4 py-2.5
                                       text-center text-[10px] font-extrabold text-white
                                       shadow-sm transition hover:bg-[#01582f]"
                            >
                                Connectez-vous pour commander
                            </a>

                        @endauth

                    </div>


                    {{-- =================================================
                        RÉASSURANCE + PARTAGE
                    ================================================== --}}
                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3">

                        <div class="flex items-center gap-3 text-[9px] font-semibold text-slate-500">

                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-[#016837]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 00-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                                Achat sécurisé
                            </span>

                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-[#F9A01B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                                Livraison rapide
                            </span>

                        </div>


                        <div class="flex items-center gap-1">

                            {{-- WhatsApp --}}
                            <a
                                href="https://api.whatsapp.com/send?text={{ urlencode($product->title . ' - ' . url()->current()) }}"
                                target="_blank"
                                class="flex h-7 w-7 items-center justify-center rounded-lg
                                       bg-[#016837]/10 text-[#016837] transition
                                       hover:bg-[#016837] hover:text-white"
                                title="Partager sur WhatsApp"
                            >

                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                </svg>

                            </a>


                            {{-- Copier --}}
                            <button
                                type="button"
                                @click="shareUrl()"
                                class="relative flex h-7 w-7 items-center justify-center rounded-lg
                                       bg-slate-100 text-slate-500 transition
                                       hover:bg-[#F9A01B]/15 hover:text-[#9a6500]"
                                title="Copier le lien"
                            >

                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                    />
                                </svg>

                                <span
                                    x-show="copied"
                                    x-transition
                                    class="absolute -top-7 left-1/2 -translate-x-1/2 whitespace-nowrap
                                           rounded bg-[#0A1B12] px-1.5 py-1 text-[8px] text-white shadow-md"
                                >
                                    Copié !
                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            DESCRIPTION + CARACTÉRISTIQUES
        ========================================================== --}}
        <div class="mb-10 grid grid-cols-1 gap-5 lg:grid-cols-3">

            {{-- Description --}}
            <div
                class="{{ $product->specifications && is_array($product->specifications) && count($product->specifications) > 0
                    ? 'lg:col-span-2'
                    : 'lg:col-span-3' }}
                    rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >

                <div class="mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">

                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#016837]/10 text-[#016837]">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M4 5h16v14H4zM8 9h8M8 13h6"
                            />
                        </svg>

                    </span>

                    <h2 class="text-sm font-extrabold text-slate-900">
                        Description du produit
                    </h2>

                </div>

                <div class="whitespace-pre-line text-[11px] leading-6 text-slate-600 sm:text-xs">
                    {{ $product->description }}
                </div>

            </div>


            {{-- Caractéristiques --}}
            @if ($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">

                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#F9A01B]/15 text-[#a96d00]">

                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-width="1.8"
                                    d="M12 3v18M3 12h18"
                                />
                            </svg>

                        </span>

                        <h2 class="text-sm font-extrabold text-slate-900">
                            Caractéristiques
                        </h2>

                    </div>

                    <div class="divide-y divide-slate-100">

                        @foreach ($product->specifications as $key => $value)

                            <div class="flex items-center justify-between gap-3 py-2.5">

                                <span class="text-[9px] font-medium text-slate-400">
                                    {{ $key }}
                                </span>

                                <span class="text-right text-[10px] font-bold text-slate-800">
                                    {{ $value }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>


        {{-- =========================================================
            AVIS CLIENTS
        ========================================================== --}}
        <div id="reviews-section" class="mb-10">

            <div class="mb-4 flex items-center justify-between">

                <div>

                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-[#016837]">
                        Expérience client
                    </p>

                    <h2 class="mt-0.5 text-base font-extrabold text-slate-900">
                        Avis clients
                    </h2>

                </div>

            </div>


            {{-- Résumé des notes --}}
            <div class="mb-4 grid grid-cols-1 gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-[150px_1fr]">

                <div class="text-center sm:border-r sm:border-slate-100">

                    <p class="text-4xl font-black text-[#0A1B12]">
                        {{ $productRating ? number_format($productRating, 1) : '—' }}
                    </p>

                    <div class="mt-1 text-lg text-[#F9A01B]">

                        @for ($i = 1; $i <= 5; $i++)
                            {{ $i <= round($productRating) ? '★' : '☆' }}
                        @endfor

                    </div>

                    <p class="mt-1 text-[9px] text-slate-400">
                        {{ $reviews->count() }} avis
                    </p>

                </div>


                <div class="space-y-1.5">

                    @for ($star = 5; $star >= 1; $star--)

                        @php
                            $count = $reviews->where('rating', $star)->count();
                            $pct = $reviews->count() > 0
                                ? ($count / $reviews->count()) * 100
                                : 0;
                        @endphp

                        <div class="flex items-center gap-2">

                            <span class="w-3 text-[9px] font-semibold text-slate-500">
                                {{ $star }}
                            </span>

                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">

                                <div
                                    class="h-full rounded-full bg-[#F9A01B]"
                                    style="width: {{ $pct }}%"
                                ></div>

                            </div>

                            <span class="w-4 text-right text-[8px] text-slate-400">
                                {{ $count }}
                            </span>

                        </div>

                    @endfor

                </div>

            </div>


            {{-- Liste des avis --}}
            @if ($reviews->count())

                <div class="space-y-3">

                    @foreach ($reviews as $review)

                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">

                            <div class="flex items-start justify-between gap-3">

                                <div>

                                    <p class="text-[11px] font-extrabold text-slate-900">
                                        {{ $review->reviewer->name }}
                                    </p>

                                    <p class="mt-0.5 text-[8px] text-slate-400">
                                        {{ $review->created_at->format('d/m/Y') }}
                                    </p>

                                </div>

                                <div class="text-sm text-[#F9A01B]">

                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                    @endfor

                                </div>

                            </div>

                            @if ($review->body)

                                <p class="mt-2 text-[10px] leading-5 text-slate-600 sm:text-[11px]">
                                    {{ $review->body }}
                                </p>

                            @endif

                            <p class="mt-2 text-[8px] font-bold text-[#016837]">
                                ✓ Achat vérifié
                            </p>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="rounded-xl border border-slate-200 bg-white p-7 text-center shadow-sm">

                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#F9A01B]/15 text-[#a96d00]">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 17h.01M12 13V7"
                            />
                        </svg>

                    </div>

                    <p class="mt-2 text-[10px] font-semibold text-slate-500">
                        Aucun avis pour l'instant.
                    </p>

                    <p class="mt-0.5 text-[9px] text-slate-400">
                        Soyez le premier à commander ce produit !
                    </p>

                </div>

            @endif

        </div>


        {{-- =========================================================
            NOTE DE LA BOUTIQUE
        ========================================================== --}}
        @if ($shopRating)

            <div class="mb-10 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="mb-3 flex items-center gap-2">

                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#016837]/10 text-[#016837]">

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 21h18M5 21V5l7-3 7 3v16M9 21v-5h6v5"
                            />
                        </svg>

                    </span>

                    <h2 class="text-sm font-extrabold text-slate-900">
                        Note de la boutique
                    </h2>

                </div>


                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                               bg-[#016837] text-xs font-extrabold uppercase text-white"
                    >
                        {{ substr($product->shop->name, 0, 2) }}
                    </div>

                    <div>

                        <p class="text-[11px] font-extrabold text-slate-900">
                            {{ $product->shop->name }}
                        </p>

                        <div class="mt-0.5 flex flex-wrap items-center gap-2">

                            <div class="text-sm text-[#F9A01B]">

                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= round($shopRating) ? '★' : '☆' }}
                                @endfor

                            </div>

                            <span class="text-[9px] text-slate-400">
                                {{ number_format($shopRating, 1) }}/5
                                — {{ $shopReviews->count() }} avis boutique
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
            AUTRES PRODUITS DE LA BOUTIQUE
        ========================================================== --}}
        @if ($shopProducts->count())

            <div class="mb-10">

                <div class="mb-4 flex items-center justify-between">

                    <div>

                        <p class="text-[9px] font-extrabold uppercase tracking-wider text-[#016837]">
                            La boutique
                        </p>

                        <h2 class="mt-0.5 text-base font-extrabold text-slate-900">
                            Autres produits de la boutique
                        </h2>

                    </div>

                    <a
                        href="{{ route('shop.show', $product->shop) }}"
                        class="text-[10px] font-extrabold text-[#016837] transition hover:text-[#014d2b]"
                    >
                        Voir tout →
                    </a>

                </div>


                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">

                    @foreach ($shopProducts as $item)

                        <a
                            href="{{ route('product.show', $item) }}"
                            class="group overflow-hidden rounded-xl border border-slate-200
                                   bg-white shadow-sm transition duration-300
                                   hover:-translate-y-0.5 hover:border-[#016837]/30 hover:shadow-md"
                        >

                            <div class="relative flex h-36 items-center justify-center overflow-hidden bg-slate-50 p-2 sm:h-40">

                                @if ($item->images->first())

                                    <img
                                        src="{{ Storage::url($item->images->first()->url) }}"
                                        alt="{{ $item->title }}"
                                        class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-105"
                                    >

                                @endif

                            </div>

                            <div class="p-2.5">

                                <h3 class="line-clamp-1 text-[10px] font-bold text-slate-800 transition group-hover:text-[#016837]">
                                    {{ $item->title }}
                                </h3>

                                <p class="mt-1.5 text-[12px] font-black text-[#016837]">
                                    {{ number_format($item->price, 0, ',', ' ') }}
                                    <span class="text-[8px]">FCFA</span>
                                </p>

                            </div>

                        </a>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- =========================================================
            PRODUITS SIMILAIRES
        ========================================================== --}}
        @if ($related->count())

            <div class="mb-10">

                <div class="mb-4">

                    <p class="text-[9px] font-extrabold uppercase tracking-wider text-[#E30613]">
                        Vous pourriez aussi aimer
                    </p>

                    <h2 class="mt-0.5 text-base font-extrabold text-slate-900">
                        Produits similaires
                    </h2>

                </div>


                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">

                    @foreach ($related as $item)

                        <a
                            href="{{ route('product.show', $item) }}"
                            class="group overflow-hidden rounded-xl border border-slate-200
                                   bg-white shadow-sm transition duration-300
                                   hover:-translate-y-0.5 hover:border-[#016837]/30 hover:shadow-md"
                        >

                            <div class="relative flex h-36 items-center justify-center overflow-hidden bg-slate-50 p-2 sm:h-40">

                                @if ($item->images->first())

                                    <img
                                        src="{{ Storage::url($item->images->first()->url) }}"
                                        alt="{{ $item->title }}"
                                        class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-105"
                                    >

                                @endif


                                @if ($item->shipping_included)

                                    <span
                                        class="absolute left-2 top-2 rounded-md bg-[#016837]/95
                                               px-1.5 py-0.5 text-[7px] font-extrabold uppercase
                                               text-white"
                                    >
                                        Transport inclus
                                    </span>

                                @endif

                                @if ($item->hasDiscount())

                                    <span
                                        class="absolute right-2 top-2 rounded-md bg-[#E30613]
                                               px-1.5 py-0.5 text-[7px] font-extrabold text-white"
                                    >
                                        -{{ $item->discountPercent() }}%
                                    </span>

                                @endif

                            </div>


                            <div class="p-2.5">

                                <h3 class="line-clamp-1 text-[10px] font-bold text-slate-800 transition group-hover:text-[#016837]">
                                    {{ $item->title }}
                                </h3>

                                <div class="mt-1.5 flex items-baseline gap-1.5">

                                    <p class="text-[12px] font-black text-[#016837]">
                                        {{ number_format($item->price, 0, ',', ' ') }}
                                        <span class="text-[8px]">FCFA</span>
                                    </p>

                                    @if ($item->hasDiscount())

                                        <p class="text-[8px] font-semibold text-[#E30613] line-through">
                                            {{ number_format($item->old_price, 0, ',', ' ') }}
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </a>

                    @endforeach

                </div>

            </div>

        @endif

    </div>


    {{-- =========================================================
        LIGHTBOX
    ========================================================== --}}
    <div
        x-show="lightboxOpen"
        x-transition.opacity
        @keydown.escape.window="lightboxOpen = false"
        class="fixed inset-0 z-[60] flex items-center justify-center bg-[#0A1B12]/95 p-4 backdrop-blur-sm"
        style="display: none;"
    >

        <button
            @click="lightboxOpen = false"
            class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center
                   rounded-full bg-white/10 text-white transition hover:bg-[#E30613]"
        >

            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>

        </button>

        <img
            :src="lightboxImg"
            alt="{{ $product->title }}"
            class="max-h-[90vh] max-w-full rounded-xl object-contain shadow-2xl"
        >

    </div>


    {{-- =========================================================
        BARRE MOBILE
    ========================================================== --}}
    <div
        class="fixed bottom-0 left-0 right-0 z-40 flex items-center justify-between
               gap-3 border-t border-slate-200 bg-white/95 p-2.5 shadow-[0_-5px_20px_rgba(0,0,0,0.08)]
               backdrop-blur-md lg:hidden"
    >

        <div class="min-w-0">

            <p class="text-[8px] font-bold uppercase tracking-wider text-slate-400">
                Prix
            </p>

            <p class="truncate text-sm font-black text-[#016837]">
                <span x-text="formatPrice(selectedVariant ? selectedVariant.price : {{ $product->minPrice() }})"></span>
                <span class="text-[9px]">FCFA</span>
            </p>

        </div>


        @auth

            @if ($product->availableStock() > 0)
                @if ($product->hasVariants())
                    <a
                        :href="selectedVariant ? '{{ route('buyer.orders.create', $product->id) }}?variant_id=' + selectedVariant.id : '#variants-section'"
                        @click="if(!selectedVariant) { $event.preventDefault(); document.getElementById('variants-section')?.scrollIntoView({behavior: 'smooth'}); }"
                        :class="selectedVariant ? 'bg-[#E30613] text-white shadow-sm' : 'bg-[#016837] text-white'"
                        class="flex items-center gap-1.5 rounded-xl px-4 py-2.5 text-[10px] font-extrabold transition active:scale-95 cursor-pointer"
                    >
                        <span x-text="selectedVariant ? 'Commander' : 'Choisir une option'">Choisir une option</span>
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                            />
                        </svg>
                    </a>
                @else
                <a
                    href="{{ route('buyer.orders.create', $product->id) }}"
                    class="flex items-center gap-1.5 rounded-xl bg-[#E30613]
                           px-4 py-2.5 text-[10px] font-extrabold text-white shadow-sm
                           transition active:scale-95"
                >
                    Commander
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                        />
                    </svg>
                </a>
                @endif

            @else

                <button
                    disabled
                    class="rounded-xl bg-slate-100 px-4 py-2.5 text-[10px] font-bold text-slate-400"
                >
                    Rupture
                </button>

            @endif

        @else

            <a
                href="{{ route('login') }}"
                class="rounded-xl bg-[#016837] px-4 py-2.5 text-[10px] font-extrabold text-white"
            >
                Connexion
            </a>

        @endauth

    </div>

</div>


{{-- =============================================================
    SCRIPTS
============================================================= --}}
@push('scripts')

<script>
    function changeMainImage(url, button) {

        const mainImage = document.getElementById('mainImage');

        if (mainImage) {
            mainImage.src = url;
        }

        document.querySelectorAll('.thumb-btn').forEach(btn => {

            btn.classList.remove(
                'border-[#016837]',
                'shadow-sm'
            );

            btn.classList.add('border-slate-200');

        });

        button.classList.remove('border-slate-200');

        button.classList.add(
            'border-[#016837]',
            'shadow-sm'
        );
    }
</script>

@endpush

@endsection