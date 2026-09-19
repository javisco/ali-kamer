<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nouveau mot de passe — {{ config('app.name', 'Ali-Kamer') }}</title>

    {{-- Importation CSS/JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50/50">

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-lg">

            <!-- Logo Ali-Kamer au-dessus de la carte -->
            <div class="text-center mb-8">
                <a href="/" class="inline-flex items-center gap-3 p-3 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-center p-1.5 rounded-xl bg-slate-50">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-14 w-auto object-contain">
                    </div>
                </a>
            </div>

            <!-- Carte Principale -->
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">

                <!-- En-tête avec les couleurs officielles Ali-Kamer -->
                <div class="bg-gradient-to-br from-primary-800 via-primary-600 to-[#046A38] text-white px-8 py-8 relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-28 h-28 rounded-full bg-warning/20 blur-lg"></div>
                    
                    <div class="relative z-10">
                        <span class="text-[11px] font-bold tracking-widest uppercase text-warning bg-white/10 px-3 py-1 rounded-full border border-white/10">
                            Sécurité du compte
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black mt-3">Nouveau mot de passe</h1>
                        <p class="mt-1.5 text-success-100/90 text-xs sm:text-sm leading-relaxed">
                            Choisissez un mot de passe fort pour sécuriser l'accès à votre compte.
                        </p>
                    </div>
                </div>

                <!-- Corps du formulaire -->
                <div class="p-6 sm:p-8">

                    <!-- Statut Succès -->
                    @if (session('status'))
                        <div class="mb-6 rounded-2xl border border-success-200 bg-success-50 px-4 py-3.5 text-xs sm:text-sm text-success-700 font-medium flex items-center gap-2">
                            <svg class="w-5 h-5 text-success shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Erreurs Globales -->
                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-danger-200 bg-danger-50 p-4 text-xs sm:text-sm text-danger-700">
                            <p class="font-bold mb-1">Attention, veuillez corriger les points suivants :</p>
                            <ul class="list-disc list-inside space-y-1 text-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Token Masqué -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email (Lecture seule) -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Adresse e-mail
                            </label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ old('email', $email) }}" readonly
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-100/80 px-4 py-3 text-sm text-slate-500 cursor-not-allowed focus:outline-none font-medium">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs">Verrouillé</span>
                            </div>
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nouveau mot de passe <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="password" required placeholder="Minimum 8 caractères"
                                class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50
                                @error('password') border-danger focus:ring-danger/20 @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 @enderror focus:outline-none">
                            @error('password')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Confirmer le mot de passe <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required placeholder="Retapez le mot de passe"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm transition bg-slate-50/50 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 focus:outline-none">
                        </div>

                        <!-- Conseils Sécurité -->
                        <div class="rounded-2xl border border-warning-200 bg-warning-50/50 p-4">
                            <h2 class="mb-2 font-bold text-warning-800 text-xs flex items-center gap-1.5">
                                <span>🛡️</span> Conseils pour un mot de passe solide :
                            </h2>
                            <ul class="list-disc pl-4 space-y-1 text-[11px] text-warning-800/90 leading-relaxed">
                                <li>Utilisez au moins 8 caractères.</li>
                                <li>Mélangez lettres majuscules, minuscules et chiffres.</li>
                                <li>Ajoutez un symbole spécial (!, @, #, $, etc.).</li>
                                <li>Évitez de réutiliser un mot de passe d'un autre site.</li>
                            </ul>
                        </div>

                        <!-- Bouton Valider -->
                        <button type="submit"
                            class="w-full rounded-2xl bg-primary-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary-600/20 transition hover:bg-primary-800 active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Mettre à jour le mot de passe</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                </div>

                <!-- Footer carte / Retour à la connexion -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 text-center">
                    <a href="{{ route('login.show') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-primary-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Retour à la connexion</span>
                    </a>
                </div>

            </div>

            <!-- Copyright -->
            <p class="text-center text-[11px] text-slate-400 mt-6">
                &copy; {{ date('Y') }} {{ config('app.name', 'Ali-Kamer') }}. Tous droits réservés.
            </p>

        </div>
    </div>

</body>
</html>