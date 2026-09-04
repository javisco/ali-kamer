@extends('layouts.guest')

@section('content')

<div class="min-h-screen bg-[#FAF9F6] py-10 px-4">

    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-8">

            <div class="flex justify-center mb-4">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Ali-Kamer"
                    class="h-16 w-auto"
                >
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-[#004d28]">
                Créez votre boutique Ali-Kamer
            </h1>

            <p class="text-gray-500 mt-2">
                Votre compte Facebook est reconnu.
                Complétez maintenant les informations de votre boutique.
            </p>

        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 md:p-8">

            {{-- Facebook identity --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 mb-7">

                @if(!empty(session('facebook_pending.avatar')))
                    <img
                        src="{{ session('facebook_pending.avatar') }}"
                        alt="Photo Facebook"
                        class="w-14 h-14 rounded-full object-cover"
                    >
                @else
                    <div class="w-14 h-14 rounded-full bg-[#006837] text-white flex items-center justify-center font-bold text-xl">
                        {{ strtoupper(substr(session('facebook_pending.name', 'A'), 0, 1)) }}
                    </div>
                @endif

                <div>
                    <p class="font-semibold text-gray-900">
                        {{ session('facebook_pending.name') }}
                    </p>

                    @if(session('facebook_pending.provider_email'))
                        <p class="text-sm text-gray-500">
                            {{ session('facebook_pending.provider_email') }}
                        </p>
                    @endif

                    <span class="inline-flex mt-1 text-xs font-medium text-[#006837]">
                        Compte Facebook vérifié
                    </span>
                </div>

            </div>

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('facebook.complete.seller.post') }}"
                class="space-y-6"
            >
                @csrf

                {{-- Paiement --}}
                <div>
                    <h2 class="text-lg font-bold text-[#004d28] mb-4">
                        Informations de paiement
                    </h2>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Numéro Mobile Money *
                            </label>

                            <input
                                type="text"
                                name="phone_momo"
                                value="{{ old('phone_momo') }}"
                                placeholder="6XXXXXXXX"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Opérateur *
                            </label>

                            <select
                                name="momo_operator"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                            >
                                <option value="">Choisir</option>
                                <option value="mtn" {{ old('momo_operator') === 'mtn' ? 'selected' : '' }}>
                                    MTN Mobile Money
                                </option>
                                <option value="orange" {{ old('momo_operator') === 'orange' ? 'selected' : '' }}>
                                    Orange Money
                                </option>
                            </select>
                        </div>

                    </div>
                </div>

                {{-- Contact --}}
                <div>
                    <h2 class="text-lg font-bold text-[#004d28] mb-4">
                        Coordonnées
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Téléphone
                            <span class="font-normal text-gray-400">
                                (facultatif)
                            </span>
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="6XXXXXXXX"
                            class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                        >
                    </div>
                </div>

                {{-- Boutique --}}
                <div>
                    <h2 class="text-lg font-bold text-[#004d28] mb-4">
                        Votre boutique
                    </h2>

                    <div class="space-y-5">

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nom de la boutique *
                            </label>

                            <input
                                type="text"
                                name="shop_name"
                                value="{{ old('shop_name') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                            >
                        </div>

                        <div class="grid md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ville *
                                </label>

                                <input
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    required
                                    placeholder="Yaoundé"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catégorie *
                                </label>

                                <input
                                    type="text"
                                    name="category"
                                    value="{{ old('category') }}"
                                    required
                                    placeholder="Électronique"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                                >
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                placeholder="Présentez brièvement votre boutique..."
                                class="w-full rounded-xl border-gray-300 focus:border-[#006837] focus:ring-[#006837] px-4 py-3"
                            >{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Information KYC --}}
                <div class="rounded-xl bg-yellow-50 border border-yellow-200 p-4">
                    <div class="flex gap-3">

                        <svg
                            class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                            />
                        </svg>

                        <div>
                            <p class="font-semibold text-yellow-800">
                                Vérification de votre boutique
                            </p>

                            <p class="text-sm text-yellow-700 mt-1">
                                Après la création de votre boutique,
                                vous serez redirigé vers l'étape de
                                vérification KYC.
                            </p>
                        </div>

                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#006837] hover:bg-[#004d28] text-white font-bold py-3.5 rounded-xl transition"
                >
                    Créer ma boutique
                </button>

            </form>

        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Ali-Kamer — Acheter et vendre sans stress.
        </p>

    </div>

</div>

@endsection