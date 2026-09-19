@extends('layouts.buyer')

@section('title', 'Noter votre commande')

@section('content')

<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="max-w-lg mx-auto px-4">

        {{-- En-tête --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-accent-500"></span>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-accent-500">
                    Votre expérience
                </span>
            </div>

            <h1 class="text-2xl font-extrabold text-slate-900">
                Votre avis
            </h1>

            <div class="flex items-center gap-2 mt-2">
                <span class="text-xs text-slate-400">Commande</span>
                <span class="inline-flex items-center px-2 py-1 rounded-lg
                             bg-primary-600/10 text-primary-600
                             text-xs font-bold">
                    {{ $order->reference }}
                </span>
            </div>
        </div>

        <form method="POST"
              action="{{ route('buyer.reviews.store', $order) }}"
              class="space-y-4">

            @csrf

            {{-- =====================================================
                 NOTE PRODUIT
            ====================================================== --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">

                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-900">
                            Note du produit
                            <span class="text-danger">*</span>
                        </label>

                        <p class="text-[11px] text-slate-400 mt-0.5">
                            Quelle est votre satisfaction ?
                        </p>
                    </div>

                    <div class="w-8 h-8 rounded-lg bg-accent-500/10
                                flex items-center justify-center">
                        <svg class="w-4 h-4 text-accent-500" fill="currentColor"
                             viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07
                                     3.292a1 1 0 00.95.69h3.462c.969 0
                                     1.371 1.24.588 1.81l-2.8 2.034a1 1
                                     0 00-.364 1.118l1.07 3.292c.3.921-.755
                                     1.688-1.538 1.118l-2.8-2.034a1 1
                                     0 00-1.175 0l-2.8 2.034c-.783.57-1.838
                                     -.197-1.539-1.118l1.07-3.292a1 1
                                     0 00-.364-1.118L2.91 8.72c-.783-.57
                                     -.38-1.81.588-1.81H6.96a1 1 0
                                     00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>

                <div class="flex gap-1.5" id="productStars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                onclick="setRating('product', {{ $i }})"
                                class="w-10 h-10 rounded-xl flex items-center justify-center
                                       text-3xl text-slate-300
                                       hover:text-accent-500 hover:bg-accent-500/10
                                       transition star-product"
                                data-value="{{ $i }}">
                            ★
                        </button>
                    @endfor
                </div>

                <input type="hidden"
                       name="product_rating"
                       id="product_rating"
                       required>

                @error('product_rating')
                    <p class="flex items-center gap-1 text-danger text-xs mt-2">
                        <span>⚠</span>
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- =====================================================
                 NOTE BOUTIQUE
            ====================================================== --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">

                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-900">
                        Note de la boutique
                        <span class="text-danger">*</span>
                    </label>

                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-5 h-5 rounded-md bg-primary-600
                                     text-white flex items-center justify-center">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M16 11c1.657 0 3-1.343 3-3s-1.343-3-3-3M8 11c-1.657
                                         0-3-1.343-3-3s1.343-3 3-3m8 6c1.657 0
                                         3 1.343 3 3v1H5v-1c0-1.657 1.343-3
                                         3-3h6z"/>
                            </svg>
                        </span>

                        <strong class="text-xs text-primary-600">
                            {{ $order->shop->name }}
                        </strong>
                    </div>
                </div>

                <div class="flex gap-1.5" id="shopStars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                onclick="setRating('shop', {{ $i }})"
                                class="w-10 h-10 rounded-xl flex items-center justify-center
                                       text-3xl text-slate-300
                                       hover:text-accent-500 hover:bg-accent-500/10
                                       transition star-shop"
                                data-value="{{ $i }}">
                            ★
                        </button>
                    @endfor
                </div>

                <input type="hidden"
                       name="shop_rating"
                       id="shop_rating"
                       required>

                @error('shop_rating')
                    <p class="flex items-center gap-1 text-danger text-xs mt-2">
                        <span>⚠</span>
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- =====================================================
                 COMMENTAIRE
            ====================================================== --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">

                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-bold text-slate-900">
                        Commentaire
                    </label>

                    <span class="text-[10px] font-medium text-slate-400">
                        Optionnel
                    </span>
                </div>

                <textarea name="body"
                          rows="4"
                          maxlength="500"
                          class="w-full border border-slate-200 rounded-xl px-4 py-3
                                 text-sm text-slate-900 bg-slate-50
                                 placeholder-slate-400
                                 focus:border-primary-600
                                 focus:ring-2 focus:ring-primary-500/15
                                 outline-none transition resize-none"
                          placeholder="Partagez votre expérience...">{{ old('body') }}</textarea>

                <div class="flex justify-end mt-1">
                    <span class="text-[10px] text-slate-300">
                        500 caractères maximum
                    </span>
                </div>

            </div>


            {{-- =====================================================
                 BOUTON
            ====================================================== --}}
            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700
                           text-white font-extrabold
                           py-3.5 rounded-2xl transition
                           shadow-sm hover:shadow-md
                           flex items-center justify-center gap-2">

                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7
                             19l-4 1 1-4L16.5 3.5z"/>
                </svg>

                Publier mon avis
            </button>

        </form>

    </div>
</div>


@push('scripts')
<script>
    // Gestion des étoiles interactives
    function setRating(type, value) {
        document.getElementById(type + '_rating').value = value;

        document.querySelectorAll('.star-' + type).forEach(star => {
            star.classList.toggle(
                'text-accent-500',
                parseInt(star.dataset.value) <= value
            );

            star.classList.toggle(
                'text-slate-300',
                parseInt(star.dataset.value) > value
            );

            star.classList.toggle(
                'bg-accent-500/10',
                parseInt(star.dataset.value) <= value
            );
        });
    }
</script>
@endpush

@endsection