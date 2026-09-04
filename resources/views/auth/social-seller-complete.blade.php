@extends('base')

@section('title', 'Créer votre boutique - Ali-Kamer')

@section('content')

<div class="min-h-screen bg-[#FAF9F6] py-10">

    <div class="max-w-6xl mx-auto px-4">

        <div class="grid lg:grid-cols-2 gap-8">

            {{-- =====================================================
                 FORMULAIRE
            ====================================================== --}}
            <div class="bg-white rounded-3xl border border-gray-100
                        shadow-sm p-6 sm:p-8">

                <div class="mb-7">

                    <span class="inline-flex items-center gap-2
                                 rounded-full bg-[#006837]/10
                                 px-3 py-1.5 text-xs font-bold text-[#006837]">
                        <span class="w-2 h-2 rounded-full bg-[#006837]"></span>
                        Inscription vendeur
                    </span>

                    <h1 class="mt-4 text-2xl sm:text-3xl font-black text-gray-900">
                        Créez votre boutique
                    </h1>

                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Votre identité Google a été récupérée.
                        Complétez maintenant les informations de votre activité.
                    </p>

                </div>


                {{-- =================================================
                     COMPTE GOOGLE
                ================================================== --}}
                <div class="mb-7 rounded-2xl bg-green-50
                            border border-green-100 p-4">

                    <div class="flex items-center gap-3">

                        @if(!empty($socialPending['avatar']))

                            <img
                                src="{{ $socialPending['avatar'] }}"
                                alt="Profil Google"
                                class="w-11 h-11 rounded-full object-cover"
                            >

                        @else

                            <div class="w-11 h-11 rounded-full bg-[#006837]
                                        flex items-center justify-center
                                        text-white font-black">
                                {{ strtoupper(
                                    substr(
                                        $socialPending['name'] ?? 'A',
                                        0,
                                        1
                                    )
                                ) }}
                            </div>

                        @endif

                        <div class="min-w-0">

                            <p class="text-sm font-bold text-gray-900 truncate">
                                {{ $socialPending['name'] }}
                            </p>

                            @if(!empty($socialPending['provider_email']))
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $socialPending['provider_email'] }}
                                </p>
                            @endif

                        </div>

                        <span class="ml-auto text-xs font-bold
                                     text-[#006837] whitespace-nowrap">
                            ✓ Google
                        </span>

                    </div>

                </div>


                {{-- =================================================
                     ERREURS
                ================================================== --}}
                @if($errors->any())

                    <div class="mb-6 rounded-2xl bg-red-50
                                border border-red-100 p-4">

                        <p class="text-sm font-bold text-red-700 mb-2">
                            Vérifiez les informations suivantes :
                        </p>

                        <ul class="space-y-1 text-xs text-red-600">

                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('social.complete.seller') }}"
                    class="space-y-6"
                >

                    @csrf


                    {{-- =================================================
                         MOBILE MONEY
                    ================================================== --}}
                    <div>

                        <h2 class="text-sm font-black text-gray-900 mb-1">
                            Paiements Mobile Money
                        </h2>

                        <p class="text-xs text-gray-500 mb-4">
                            Ces informations seront utilisées pour vos paiements vendeur.
                        </p>


                        <div class="mb-4">

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Numéro Mobile Money
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="phone_momo"
                                value="{{ old('phone_momo') }}"
                                placeholder="6XXXXXXXX"
                                required
                                class="w-full rounded-xl border border-gray-200
                                       px-4 py-3 text-sm outline-none
                                       focus:border-[#006837]
                                       focus:ring-2 focus:ring-[#006837]/10"
                            >

                            @error('phone_momo')
                                <p class="mt-1 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Opérateur
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-2 gap-3">

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="momo_operator"
                                    value="mtn"
                                    class="peer sr-only"
                                    {{ old('momo_operator') === 'mtn' ? 'checked' : '' }}
                                >

                                <div class="rounded-xl border border-gray-200
                                            px-4 py-3 text-center font-bold
                                            peer-checked:border-[#FFC20E]
                                            peer-checked:bg-yellow-50
                                            transition">
                                    MTN
                                </div>

                            </label>


                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="momo_operator"
                                    value="orange"
                                    class="peer sr-only"
                                    {{ old('momo_operator') === 'orange' ? 'checked' : '' }}
                                >

                                <div class="rounded-xl border border-gray-200
                                            px-4 py-3 text-center font-bold
                                            peer-checked:border-[#FF7900]
                                            peer-checked:bg-orange-50
                                            transition">
                                    Orange
                                </div>

                            </label>

                        </div>

                        @error('momo_operator')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- =================================================
                         TELEPHONE
                    ================================================== --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Téléphone
                            <span class="text-xs font-normal text-gray-400">
                                (optionnel)
                            </span>
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="6XXXXXXXX"
                            class="w-full rounded-xl border border-gray-200
                                   px-4 py-3 text-sm outline-none
                                   focus:border-[#006837]
                                   focus:ring-2 focus:ring-[#006837]/10"
                        >

                    </div>


                    {{-- =================================================
                         BOUTIQUE
                    ================================================== --}}
                    <div>

                        <h2 class="text-sm font-black text-gray-900 mb-1">
                            Votre boutique
                        </h2>

                        <p class="text-xs text-gray-500 mb-4">
                            Ces informations permettront aux acheteurs de découvrir votre activité.
                        </p>


                        <div class="space-y-4">

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom de la boutique
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="shop_name"
                                    value="{{ old('shop_name') }}"
                                    placeholder="Ex : Ma Boutique"
                                    required
                                    class="w-full rounded-xl border border-gray-200
                                           px-4 py-3 text-sm outline-none
                                           focus:border-[#006837]
                                           focus:ring-2 focus:ring-[#006837]/10"
                                >

                            </div>


                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ville
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    placeholder="Ex : Yaoundé"
                                    required
                                    class="w-full rounded-xl border border-gray-200
                                           px-4 py-3 text-sm outline-none
                                           focus:border-[#006837]
                                           focus:ring-2 focus:ring-[#006837]/10"
                                >

                            </div>


                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catégorie
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="category"
                                    value="{{ old('category') }}"
                                    placeholder="Ex : Électronique"
                                    required
                                    class="w-full rounded-xl border border-gray-200
                                           px-4 py-3 text-sm outline-none
                                           focus:border-[#006837]
                                           focus:ring-2 focus:ring-[#006837]/10"
                                >

                            </div>


                            <div>

                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                    <span class="text-xs font-normal text-gray-400">
                                        (optionnelle)
                                    </span>
                                </label>

                                <textarea
                                    name="description"
                                    rows="4"
                                    placeholder="Présentez brièvement votre boutique..."
                                    class="w-full rounded-xl border border-gray-200
                                           px-4 py-3 text-sm outline-none resize-none
                                           focus:border-[#006837]
                                           focus:ring-2 focus:ring-[#006837]/10"
                                >{{ old('description') }}</textarea>

                            </div>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="w-full rounded-xl bg-[#006837]
                               px-5 py-3.5 text-sm font-extrabold text-white
                               hover:bg-[#004d28] transition shadow-sm"
                    >
                        Créer ma boutique
                    </button>

                    <p class="text-center text-xs text-gray-400">
                        Après la création de votre boutique,
                        vous serez redirigé vers l'étape suivante de votre inscription vendeur.
                    </p>

                </form>

            </div>


            {{-- =====================================================
                 PANNEAU DROIT
            ====================================================== --}}
            <div class="hidden lg:flex rounded-3xl overflow-hidden
                        bg-[#006837] relative">

                <div class="absolute inset-0 bg-gradient-to-br
                            from-[#006837] to-[#004d28]">
                </div>

                <div class="relative z-10 p-10 text-white
                            flex flex-col justify-between">

                    <div>

                        <span class="inline-flex rounded-full
                                     bg-white/10 border border-white/10
                                     px-3 py-1.5 text-xs font-bold">
                            🇨🇲 Ali-Kamer
                        </span>

                        <h2 class="mt-7 text-3xl font-black leading-tight">
                            Vendez au Cameroun.
                            <br>
                            Développez votre activité.
                        </h2>

                        <p class="mt-5 text-sm leading-6 text-white/80">
                            Créez votre boutique et présentez vos produits
                            aux acheteurs partout au Cameroun.
                        </p>

                    </div>


                    <div class="space-y-5">

                        <div class="flex items-start gap-3">

                            <div class="w-9 h-9 rounded-xl bg-[#FFC20E]
                                        text-gray-900 flex items-center
                                        justify-center font-black">
                                1
                            </div>

                            <div>
                                <p class="font-bold">
                                    Créez votre boutique
                                </p>

                                <p class="text-xs text-white/70 mt-1">
                                    Présentez votre activité.
                                </p>
                            </div>

                        </div>


                        <div class="flex items-start gap-3">

                            <div class="w-9 h-9 rounded-xl bg-[#CE1126]
                                        text-white flex items-center
                                        justify-center font-black">
                                2
                            </div>

                            <div>
                                <p class="font-bold">
                                    Ajoutez vos produits
                                </p>

                                <p class="text-xs text-white/70 mt-1">
                                    Commencez à vendre sur Ali-Kamer.
                                </p>
                            </div>

                        </div>


                        <div class="flex items-start gap-3">

                            <div class="w-9 h-9 rounded-xl bg-[#FFC20E]
                                        text-gray-900 flex items-center
                                        justify-center font-black">
                                3
                            </div>

                            <div>
                                <p class="font-bold">
                                    Développez vos ventes
                                </p>

                                <p class="text-xs text-white/70 mt-1">
                                    Touchez davantage de clients.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection