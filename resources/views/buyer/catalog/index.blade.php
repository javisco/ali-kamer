@extends('base')

@section('title', 'Ali-Kamer — Produits et marketplace au Cameroun')

@section('content')

<div class="min-h-screen bg-slate-50 pb-12">

{{-- =========================================================
    1. HAUT DE PAGE — INTRODUCTION + RECHERCHE
========================================================== --}}
<section class="bg-white border-b border-slate-200">

    <div class="max-w-[1500px] mx-auto px-4 sm:px-6 py-5">

        <div class="flex flex-col lg:flex-row lg:items-center gap-5">

            {{-- -----------------------------------------------------
                TEXTE D'ACCUEIL
            ------------------------------------------------------ --}}
            <div class="flex-1 min-w-0">

                <div class="flex items-center gap-2 mb-2">

                    <span class="inline-flex items-center gap-1.5
                                 px-2.5 py-1
                                 rounded-full
                                 bg-orange-50
                                 border border-orange-200
                                 text-[10px]
                                 font-black
                                 text-orange-600
                                 uppercase
                                 tracking-wide">

                        🇨🇲
                        Marketplace camerounaise

                    </span>

                    <span class="hidden sm:inline text-[10px] text-slate-400">
                        •
                    </span>

                    <span class="hidden sm:inline text-[10px] font-medium text-slate-400">
                        Achat sécurisé
                    </span>

                </div>


                <h1 class="text-xl sm:text-2xl lg:text-[27px]
                           leading-tight
                           font-black
                           tracking-tight
                           text-slate-900">

                    Trouvez ce dont vous avez besoin,
                    <span class="text-orange-600">
                        au Cameroun
                    </span>

                </h1>


                <p class="mt-1.5
                          text-xs sm:text-sm
                          leading-relaxed
                          text-slate-500
                          max-w-2xl">

                    Découvrez des produits proposés par des vendeurs
                    de confiance et achetez avec la protection de paiement
                    Ali-Kamer.

                </p>


                {{-- Petites informations de confiance --}}
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-3">

                    <div class="flex items-center gap-1.5">

                        <span class="flex items-center justify-center
                                     w-5 h-5
                                     rounded-full
                                     bg-emerald-50
                                     text-emerald-600">

                            <svg class="w-3 h-3"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>

                            </svg>

                        </span>

                        <span class="text-[10px] sm:text-[11px]
                                     font-semibold text-slate-500">

                            Paiement protégé

                        </span>

                    </div>


                    <div class="flex items-center gap-1.5">

                        <span class="flex items-center justify-center
                                     w-5 h-5
                                     rounded-full
                                     bg-blue-50
                                     text-blue-600">

                            <svg class="w-3 h-3"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM5 21a7 7 0 0114 0"/>

                            </svg>

                        </span>

                        <span class="text-[10px] sm:text-[11px]
                                     font-semibold text-slate-500">

                            Vendeurs vérifiés

                        </span>

                    </div>


                    <div class="flex items-center gap-1.5">

                        <span class="flex items-center justify-center
                                     w-5 h-5
                                     rounded-full
                                     bg-orange-50
                                     text-orange-600">

                            <svg class="w-3 h-3"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M4 18h16M12 3l9 5H3l9-5z"/>

                            </svg>

                        </span>

                        <span class="text-[10px] sm:text-[11px]
                                     font-semibold text-slate-500">

                            Produits au Cameroun

                        </span>

                    </div>

                </div>

            </div>


            {{-- -----------------------------------------------------
                BARRE DE RECHERCHE
            ------------------------------------------------------ --}}
            <div class="w-full lg:w-[500px] xl:w-[530px] shrink-0">

                <form method="GET"
                      action="{{ route('buyer.home') }}">

                    @if (request('category'))
                        <input type="hidden"
                               name="category"
                               value="{{ request('category') }}">
                    @endif

                    @if (request('city'))
                        <input type="hidden"
                               name="city"
                               value="{{ request('city') }}">
                    @endif

                    @if (request('min_price'))
                        <input type="hidden"
                               name="min_price"
                               value="{{ request('min_price') }}">
                    @endif

                    @if (request('max_price'))
                        <input type="hidden"
                               name="max_price"
                               value="{{ request('max_price') }}">
                    @endif

                    @if (request('shipping'))
                        <input type="hidden"
                               name="shipping"
                               value="{{ request('shipping') }}">
                    @endif


                    <div class="relative flex items-center">

                        <svg class="absolute left-4
                                    w-5 h-5
                                    text-slate-400
                                    pointer-events-none"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1 14 0 7 7 0 0 1 14 0z"/>

                        </svg>


                        <input type="search"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Rechercher un produit, une ville..."
                               autocomplete="off"
                               class="w-full
                                      h-12
                                      pl-11
                                      pr-32
                                      rounded-2xl
                                      bg-slate-50
                                      border border-slate-300
                                      text-sm
                                      font-medium
                                      text-slate-900
                                      placeholder:text-slate-400
                                      outline-none
                                      focus:bg-white
                                      focus:border-blue-600
                                      focus:ring-4
                                      focus:ring-blue-600/10
                                      transition">


                        <button type="submit"
                                class="absolute
                                       right-1.5
                                       top-1.5
                                       bottom-1.5
                                       px-5
                                       rounded-xl
                                       bg-blue-600
                                       hover:bg-blue-700
                                       active:scale-[0.98]
                                       text-white
                                       text-xs
                                       font-black
                                       transition
                                       shadow-sm">

                            Rechercher

                        </button>

                    </div>

                </form>


                {{-- Suggestions sous la recherche --}}
                <div class="flex flex-wrap items-center gap-2 mt-2">

                    <span class="text-[10px] font-semibold text-slate-400">
                        Recherches :
                    </span>

                    <a href="{{ route('buyer.home', ['q' => 'téléphone']) }}"
                       class="text-[10px] text-slate-500 hover:text-blue-600 transition">

                        Téléphones

                    </a>

                    <span class="text-slate-300">•</span>

                    <a href="{{ route('buyer.home', ['q' => 'chaussures']) }}"
                       class="text-[10px] text-slate-500 hover:text-blue-600 transition">

                        Chaussures

                    </a>

                    <span class="text-slate-300">•</span>

                    <a href="{{ route('buyer.home', ['q' => 'ordinateur']) }}"
                       class="text-[10px] text-slate-500 hover:text-blue-600 transition">

                        Ordinateurs

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    2. CONTENU PRINCIPAL
========================================================== --}}
<div class="max-w-[1500px] mx-auto px-4 sm:px-6 pt-5">


{{-- =========================================================
    3. CATÉGORIES
========================================================== --}}
<section class="mb-6">

    <div class="flex items-center justify-between mb-3">

        <div>

            <h2 class="text-sm sm:text-base
                       font-black
                       text-slate-900">

                Explorer les catégories

            </h2>

            <p class="text-[10px] sm:text-[11px]
                      text-slate-400
                      mt-0.5">

                Trouvez rapidement ce que vous recherchez

            </p>

        </div>


        @if (request('category'))

            <a href="{{ route('buyer.home') }}"
               class="text-[11px]
                      font-bold
                      text-blue-600
                      hover:text-blue-700
                      whitespace-nowrap">

                Tout afficher →

            </a>

        @endif

    </div>


    @php

        $categories = [

            [
                'name' => 'Électronique',
                'slug' => 'electronique',
                'icon' => '💻'
            ],

            [
                'name' => 'Mode & Beauté',
                'slug' => 'mode',
                'icon' => '👕'
            ],

            [
                'name' => 'Maison & Bureau',
                'slug' => 'maison',
                'icon' => '🏠'
            ],

            [
                'name' => 'Agriculture',
                'slug' => 'agriculture',
                'icon' => '🌾'
            ],

        ];

    @endphp


    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">

        @foreach ($categories as $category)

            <a href="{{ route('buyer.home', ['category' => $category['slug']]) }}"
               class="group
                      flex items-center gap-3
                      px-3.5 py-2.5
                      rounded-xl
                      border
                      transition-all duration-200
                      {{ request('category') === $category['slug']

                          ? 'bg-blue-600 border-blue-600 text-white shadow-md shadow-blue-100'

                          : 'bg-white border-slate-200 text-slate-700 hover:border-blue-300 hover:shadow-sm'
                      }}">

                <span class="flex items-center justify-center
                             w-9 h-9
                             shrink-0
                             rounded-lg
                             text-lg
                             {{ request('category') === $category['slug']

                                 ? 'bg-white/15'

                                 : 'bg-slate-50 group-hover:bg-blue-50'
                             }}">

                    {{ $category['icon'] }}

                </span>


                <div class="min-w-0">

                    <span class="block
                                 text-xs sm:text-sm
                                 font-extrabold
                                 truncate">

                        {{ $category['name'] }}

                    </span>

                    <span class="block
                                 text-[9px]
                                 mt-0.5
                                 {{ request('category') === $category['slug']

                                     ? 'text-white/70'

                                     : 'text-slate-400'
                                 }}">

                        Découvrir les produits

                    </span>

                </div>


                <svg class="w-4 h-4 ml-auto shrink-0
                            opacity-40
                            group-hover:opacity-100
                            group-hover:translate-x-0.5
                            transition"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="m9 5 7 7-7 7"/>

                </svg>

            </a>

        @endforeach

    </div>

</section>


{{-- =========================================================
    4. EN-TÊTE DES PRODUITS
========================================================== --}}
<section class="mb-3">

    <div class="flex items-end justify-between
                gap-3
                pb-2.5
                border-b border-slate-200">

        <div class="min-w-0">

            <h2 class="text-base sm:text-lg
                       font-black
                       text-slate-900
                       truncate">

                @if (request('q'))

                    Résultats pour

                    <span class="text-blue-600">
                        "{{ request('q') }}"
                    </span>

                @elseif(request('category'))

                    Produits de la catégorie

                    <span class="text-blue-600 uppercase">
                        {{ request('category') }}
                    </span>

                @else

                    Offres & produits populaires

                @endif

            </h2>


            <p class="text-[10px] sm:text-[11px]
                      text-slate-400
                      mt-0.5">

                {{ $products->total() }}

                {{ $products->total() > 1
                    ? 'produits disponibles'
                    : 'produit disponible'
                }}

            </p>

        </div>


        @if (request('q') || request('category'))

            <a href="{{ route('buyer.home') }}"
               class="shrink-0
                      text-[10px] sm:text-[11px]
                      font-bold
                      text-blue-600
                      hover:text-blue-700">

                Réinitialiser

            </a>

        @endif

    </div>

</section>


{{-- =========================================================
    5. GRILLE DES PRODUITS
========================================================== --}}
<section>

    <div class="grid
                grid-cols-2
                sm:grid-cols-3
                md:grid-cols-4
                lg:grid-cols-5
                xl:grid-cols-6
                gap-2.5
                sm:gap-3">


        @forelse($products as $product)

            @php

                /*
                |--------------------------------------------------------------------------
                | STOCK DISPONIBLE
                |--------------------------------------------------------------------------
                */
                $availableStock = max(
                    0,
                    (int) $product->stock - (int) $product->stock_reserved
                );


                /*
                |--------------------------------------------------------------------------
                | RÉDUCTION
                |--------------------------------------------------------------------------
                */
                $hasDiscount =
                    $product->old_price &&
                    $product->old_price > $product->price;

                $discountPercent = 0;

                if ($hasDiscount && $product->old_price > 0) {

                    $discountPercent = (int) round(
                        (
                            ($product->old_price - $product->price)
                            / $product->old_price
                        ) * 100
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | QUANTITÉ MINIMALE
                |--------------------------------------------------------------------------
                */
                $minQuantity = max(
                    1,
                    (int) ($product->min_quantity ?? 1)
                );


                /*
                |--------------------------------------------------------------------------
                | ANCIENNETÉ DU VENDEUR
                |--------------------------------------------------------------------------
                */
                $sellerAge = null;

                if ($product->shop?->user?->created_at) {

                    $createdAt = $product->shop->user->created_at;
                    $now = now();

                    $years = $createdAt->diffInYears($now);

                    if ($years >= 1) {

                        $sellerAge = $years . ' ' .
                            ($years > 1 ? 'ans' : 'an');

                    } else {

                        $months = $createdAt->diffInMonths($now);

                        if ($months >= 1) {

                            $sellerAge = $months . ' mois';

                        } else {

                            $days = $createdAt->diffInDays($now);

                            if ($days >= 1) {

                                $sellerAge = $days . ' ' .
                                    ($days > 1 ? 'jours' : 'jour');

                            } else {

                                $sellerAge = 'moins d’un jour';

                            }

                        }

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | NOMBRE DE VENTES
                |--------------------------------------------------------------------------
                */
                $salesCount = \App\Models\OrderItem::query()
                    ->where('product_id', $product->id)
                    ->whereHas('order', function ($query) {

                        $query->whereIn('status', [

                            \App\Models\Order::STATUS_COMPLETED,

                            \App\Models\Order::STATUS_AUTO_COMPLETED,

                        ]);

                    })
                    ->sum('quantity');

            @endphp


            {{-- =================================================
                CARTE PRODUIT
            ================================================== --}}
            <a href="{{ route('product.show', $product) }}"
               class="group
                      min-w-0
                      bg-white
                      border border-slate-200
                      rounded-xl
                      overflow-hidden
                      hover:border-blue-400
                      hover:shadow-lg
                      transition-all duration-200">


                {{-- =================================================
                    IMAGE
                ================================================== --}}
                <div class="relative
                            aspect-square
                            bg-slate-100
                            overflow-hidden">

                    @if ($product->images && $product->images->first())

                        <img
                            src="{{ Storage::url($product->images->first()->url) }}"
                            alt="{{ $product->title }}"
                            loading="lazy"
                            class="w-full h-full
                                   object-cover
                                   group-hover:scale-[1.04]
                                   transition-transform duration-300"
                        >

                    @else

                        <div class="w-full h-full
                                    flex items-center justify-center
                                    bg-slate-50">

                            <svg class="w-9 h-9 text-slate-300"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2v12a2 2 0 002 2z"/>

                            </svg>

                        </div>

                    @endif


                    {{-- Transport --}}
                    @if ($product->shipping_included)

                        <span class="absolute
                                     top-2 left-2
                                     px-1.5 py-1
                                     rounded-md
                                     bg-emerald-600
                                     text-white
                                     text-[9px]
                                     font-black
                                     shadow-sm">

                            Transport inclus

                        </span>

                    @endif


                    {{-- Réduction --}}
                    @if ($hasDiscount && $discountPercent > 0)

                        <span class="absolute
                                     top-2 right-2
                                     px-2 py-1
                                     rounded-md
                                     bg-red-600
                                     text-white
                                     text-[10px]
                                     font-black
                                     shadow-sm">

                            -{{ $discountPercent }}%

                        </span>

                    @endif


                    {{-- Stock --}}
                    @if ($availableStock <= 0 || $product->status === 'sold_out')

                        <span class="absolute
                                     bottom-2 right-2
                                     px-1.5 py-1
                                     rounded-md
                                     bg-slate-900/90
                                     text-white
                                     text-[9px]
                                     font-bold">

                            Épuisé

                        </span>

                    @elseif ($availableStock <= 5)

                        <span class="absolute
                                     bottom-2 right-2
                                     px-1.5 py-1
                                     rounded-md
                                     bg-amber-500
                                     text-white
                                     text-[9px]
                                     font-bold">

                            {{ $availableStock }} restant(s)

                        </span>

                    @endif

                </div>


                {{-- =================================================
                    INFORMATIONS
                ================================================== --}}
                <div class="px-3 pt-2.5 pb-3">


                    {{-- =================================================
                        BOUTIQUE
                    ================================================== --}}
                    @if ($product->shop)

                        <div class="flex items-center gap-1.5 min-w-0">

                            <span class="text-[10px] sm:text-[11px]
                                         font-bold
                                         text-slate-500
                                         truncate">

                                {{ $product->shop->name }}

                            </span>


                            @if ($product->shop->verified_at)

                                <svg class="w-3.5 h-3.5
                                            text-blue-500
                                            shrink-0"
                                     fill="currentColor"
                                     viewBox="0 0 20 20">

                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 001.414 1.414L9 12.414l4.707-4.707z"
                                        clip-rule="evenodd"
                                    />

                                </svg>

                            @endif

                        </div>


                        {{-- =================================================
                            VILLE + ANCIENNETÉ
                        ================================================== --}}
                        <div class="flex items-center
                                    gap-1.5
                                    mt-1
                                    text-[10px] sm:text-[11px]
                                    text-slate-400
                                    whitespace-nowrap
                                    overflow-hidden">

                            <span class="truncate">

                                {{ $product->city
                                    ?? $product->shop->city
                                    ?? 'Cameroun'
                                }}

                            </span>


                            @if ($sellerAge)

                                <span class="text-slate-300 shrink-0">
                                    ·
                                </span>

                                <span class="shrink-0 font-medium">

                                    {{ $sellerAge }}

                                </span>

                            @endif

                        </div>

                    @endif


                    {{-- =================================================
                        TITRE PRODUIT
                    ================================================== --}}
                    <h3 class="mt-2
                               text-[13px] sm:text-[14px]
                               font-medium
                               text-slate-800
                               leading-[1.35]
                               line-clamp-2
                               min-h-[38px]
                               group-hover:text-blue-600
                               transition-colors">

                        {{ $product->title }}

                    </h3>


                    {{-- =================================================
                        VENTES
                    ================================================== --}}
                    <div class="mt-1.5
                                text-[10px] sm:text-[11px]
                                text-slate-500">

                        <span class="font-bold text-slate-700">

                            {{ number_format($salesCount, 0, ',', ' ') }}

                        </span>

                        {{ $salesCount > 1 ? 'ventes' : 'vente' }}

                    </div>


                    {{-- =================================================
                        PRIX + QUANTITÉ MINIMALE
                    ================================================== --}}
                    <div class="mt-2.5">


                        <div class="flex items-center
                                    gap-2
                                    flex-wrap">


                            {{-- Prix actuel --}}
                            <div class="flex items-baseline gap-1">

                                <span class="text-[17px] sm:text-[18px]
                                             font-black
                                             tracking-tight
                                             text-slate-900
                                             leading-none">

                                    {{ number_format($product->price, 0, ',', ' ') }}

                                </span>

                                <span class="text-[9px] sm:text-[10px]
                                             font-bold
                                             text-slate-500">

                                    FCFA

                                </span>

                            </div>


                            {{-- Quantité minimale --}}
                            <span class="inline-flex
                                         items-center
                                         px-2 py-1
                                         rounded-md
                                         bg-blue-50
                                         border border-blue-100
                                         text-[10px] sm:text-[11px]
                                         font-black
                                         text-blue-700
                                         whitespace-nowrap">

                                Min. {{ number_format($minQuantity, 0, ',', ' ') }}

                            </span>

                        </div>


                        {{-- =================================================
                            ANCIEN PRIX
                        ================================================== --}}
                        @if ($hasDiscount)

                            <div class="flex items-center
                                        gap-2
                                        mt-1.5">

                                <span class="text-[11px] sm:text-xs
                                             font-semibold
                                             text-red-500
                                             line-through
                                             decoration-red-500
                                             decoration-1">

                                    {{ number_format($product->old_price, 0, ',', ' ') }}
                                    FCFA

                                </span>


                                <span class="text-[10px]
                                             font-black
                                             text-red-600">

                                    -{{ $discountPercent }}%

                                </span>

                            </div>

                        @endif

                    </div>

                </div>

            </a>


        @empty


            {{-- =================================================
                AUCUN PRODUIT
            ================================================== --}}
            <div class="col-span-full">

                <div class="bg-white
                            border border-dashed
                            border-slate-300
                            rounded-2xl
                            py-14 px-5
                            text-center">

                    <div class="w-12 h-12
                                mx-auto
                                rounded-xl
                                bg-slate-100
                                flex items-center justify-center
                                mb-3">

                        <svg class="w-6 h-6 text-slate-400"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.5"
                                  d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1 14 0 7 7 0 0 1 14 0z"/>

                        </svg>

                    </div>


                    <h3 class="text-base
                               font-bold
                               text-slate-800">

                        Aucun produit trouvé

                    </h3>


                    <p class="mt-1
                              text-xs
                              text-slate-400">

                        Essayez de modifier votre recherche ou vos filtres.

                    </p>


                    @if (request('q') || request('category'))

                        <a href="{{ route('buyer.home') }}"
                           class="inline-flex
                                  mt-4
                                  px-4 py-2
                                  rounded-lg
                                  bg-blue-600
                                  hover:bg-blue-700
                                  text-white
                                  text-xs
                                  font-bold
                                  transition">

                            Voir tous les produits

                        </a>

                    @endif

                </div>

            </div>

        @endforelse

    </div>


    {{-- =========================================================
        PAGINATION
    ========================================================== --}}
    @if ($products->hasPages())

        <div class="mt-7 flex justify-center">

            {{ $products->links() }}

        </div>

    @endif

</section>


</div>

</div>

@endsection