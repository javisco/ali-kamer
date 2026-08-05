@extends('base')

@section('title', 'Créer un compte')

@section('content')

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-white py-5">

        <div class="max-w-5xl mx-auto px-4">

            @if (session('fail'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.6 20h14.8a2 2 0 001.71-3.32L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                        <span class="text-red-700 font-medium">
                            {{ session('fail') }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="grid lg:grid-cols-5 rounded-3xl overflow-hidden shadow-2xl bg-white">

                {{-- ======================================================
                    Colonne de gauche Premium
                ======================================================= --}}
                <div class="lg:col-span-2 relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 text-white">

                    {{-- Décor de fond --}}
                    <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute -bottom-28 -left-24 w-80 h-80 rounded-full bg-cyan-300/10 blur-3xl"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_45%)]"></div>

                    <div class="relative z-10 flex flex-col justify-between h-full p-10">

                        {{-- Logo --}}
                        <div>
                            <div class="inline-flex rounded-3xl bg-white p-4 shadow-2xl">
                                <img src="{{ asset('images/logo.png') }}" alt="Ali-Kamer" class="w-28 h-28 object-contain">
                            </div>

                            <h1 class="mt-8 text-5xl font-black tracking-tight">
                                Ali-Kamer
                            </h1>

                            <p class="mt-3 text-xl text-blue-100 font-medium">
                                Achetez et vendez sans stress.
                            </p>

                            <p class="mt-8 text-blue-100 leading-8">
                                La marketplace conçue pour connecter acheteurs, vendeurs et agences partout au Cameroun.
                                <br><br>
                                Paiements sécurisés, suivi des commandes, boutiques vérifiées et assistance rapide.
                            </p>
                        </div>

                        {{-- Avantages --}}
                        <div class="space-y-5 mt-12">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-xl">
                                    🔒
                                </div>
                                <div>
                                    <div class="font-bold">Paiements sécurisés</div>
                                    <div class="text-sm text-blue-100">Transactions protégées avec séquestre.</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-xl">
                                    📦
                                </div>
                                <div>
                                    <div class="font-bold">Livraison suivie</div>
                                    <div class="text-sm text-blue-100">Chaque commande est suivie jusqu'à réception.</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-xl">
                                    🛍️
                                </div>
                                <div>
                                    <div class="font-bold">Des milliers de produits</div>
                                    <div class="text-sm text-blue-100">Achetez auprès de vendeurs partout au Cameroun.</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-xl">
                                    ⭐
                                </div>
                                <div>
                                    <div class="font-bold">Marketplace de confiance</div>
                                    <div class="text-sm text-blue-100">Pensée pour inspirer confiance dès la première visite.</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ======================================================
                    Colonne de droite (Formulaire)
                ======================================================= --}}
                <div class="lg:col-span-3 p-8 lg:p-12">

                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-slate-800">
                            Créer un compte
                        </h2>
                        <p class="text-slate-500 mt-2">
                            Remplissez les informations ci-dessous pour commencer.
                        </p>
                    </div>

                    <form action="{{ route('register') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Nom + Email --}}
                        <div class="grid md:grid-cols-2 gap-5">

                            {{-- Nom --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nom complet
                                </label>
                                <div class="relative rounded-2xl border border-slate-300 focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100 transition">
                                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A11.955 11.955 0 0112 15c2.5 0 4.82.76 6.879 2.056M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Jean Dupont"
                                        class="w-full rounded-2xl py-4 pl-12 pr-4 outline-none border-0 bg-transparent">
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Adresse e-mail
                                </label>
                                <div class="relative rounded-2xl border border-slate-300 focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100 transition">
                                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8-4H8m10-3H6a2 2 0 00-2 2v10a2 2 0 002 h12a2 2 0 002-2V7a2 2 0 00-2-2z" />
                                    </svg>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nom@email.com"
                                        class="w-full rounded-2xl py-4 pl-12 pr-4 outline-none border-0 bg-transparent">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Téléphone
                            </label>
                            <div class="flex rounded-2xl border border-slate-300 overflow-hidden focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100 transition">
                                <div class="px-5 flex items-center bg-slate-50 border-r text-slate-600 font-semibold">
                                    🇨🇲 +237
                                </div>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="6 XX XX XX XX"
                                    class="flex-1 py-4 px-4 outline-none border-0">
                            </div>
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mot de passe + Confirmation --}}
                        <div class="grid md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Mot de passe
                                </label>
                                <div class="relative rounded-2xl border border-slate-300 focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100">
                                    <input id="password" type="password" name="password" placeholder="Minimum 8 caractères"
                                        class="w-full rounded-2xl py-4 px-4 pr-14 border-0 outline-none">
                                    <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600">
                                        👁
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Confirmation
                                </label>
                                <div class="relative rounded-2xl border border-slate-300 focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100">
                                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirmez le mot de passe"
                                        class="w-full rounded-2xl py-4 px-4 pr-14 border-0 outline-none">
                                    <button type="button" id="togglePasswordConfirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600">
                                        👁
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Choix du Rôle --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">
                                Vous souhaitez rejoindre ALI-KAMER en tant que :
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                
                                <!-- Acheteur -->
                                <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-2xl cursor-pointer transition hover:border-blue-500 hover:bg-blue-50/40 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                    <input type="radio" name="role" value="buyer" class="mt-1 text-blue-600 focus:ring-blue-500" {{ old('role', 'buyer') == 'buyer' ? 'checked' : '' }}>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm sm:text-base">Acheteur</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Acheter des produits</p>
                                    </div>
                                </label>

                                <!-- Vendeur -->
                                <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-2xl cursor-pointer transition hover:border-blue-500 hover:bg-blue-50/40 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                    <input type="radio" name="role" value="seller" class="mt-1 text-blue-600 focus:ring-blue-500" {{ old('role') == 'seller' ? 'checked' : '' }}>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm sm:text-base">Vendeur</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Ouvrir une boutique</p>
                                    </div>
                                </label>

                                <!-- Secrétaire -->
                                <label class="flex items-start gap-3 p-4 border border-slate-200 rounded-2xl cursor-pointer transition hover:border-blue-500 hover:bg-blue-50/40 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-600">
                                    <input type="radio" name="role" value="secretary" class="mt-1 text-blue-600 focus:ring-blue-500" {{ old('role') == 'secretary' ? 'checked' : '' }}>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm sm:text-base">Secrétaire</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Gestion d'agence</p>
                                    </div>
                                </label>

                            </div>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bouton Submit --}}
                        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 py-4 text-base font-bold text-white transition hover:from-blue-800 hover:to-blue-700 shadow-lg shadow-blue-200 mt-4">
                            Créer mon compte
                        </button>

                    </form>

                    {{-- Lien Se connecter --}}
                    <div class="mt-8 border-t border-slate-100 pt-6 text-center text-slate-600 flex items-center justify-center gap-2">
                        <span>Vous avez déjà un compte ?</span>
                        <a href="{{ route('login.show') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                            Se connecter
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Script pour la bascule de visibilité des mots de passe --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupToggle(buttonId, inputId) {
                const btn = document.getElementById(buttonId);
                const input = document.getElementById(inputId);
                if (btn && input) {
                    btn.addEventListener('click', () => {
                        const isPassword = input.type === 'password';
                        input.type = isPassword ? 'text' : 'password';
                    });
                }
            }
            setupToggle('togglePassword', 'password');
            setupToggle('togglePasswordConfirmation', 'password_confirmation');
        });
    </script>

@endsection