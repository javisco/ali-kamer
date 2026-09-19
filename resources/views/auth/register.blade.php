<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Créer un compte — {{ config('app.name', 'Ali-Kamer') }}</title>

    {{-- Importation CSS/JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800">

    <!-- Conteneur principal -->
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-slate-50/50">
        <div class="w-full max-w-md">

            {{-- Alerte d'erreur --}}
            @if (session('fail'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-danger-200 bg-danger-50 p-4 text-sm text-danger-700 shadow-sm">
                    <svg class="h-5 w-5 shrink-0 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('fail') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

                <!-- En-tête avec Logo (Identique au Login) -->
                <div class="bg-gradient-to-br from-primary-900 via-primary-700 to-primary-600 px-6 pt-8 pb-6 text-center relative overflow-hidden">
                    {{-- Décor de fond subtil --}}
                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute -bottom-12 -left-12 w-32 h-32 rounded-full bg-primary-300/10 blur-xl"></div>

                    <div class="relative z-10">
                        <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-xl mb-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Ali-Kamer Logo" class="h-14 w-auto object-contain">
                        </div>
                        <h1 class="text-2xl font-black tracking-tight text-white">Créer un compte</h1>
                        <p class="text-xs text-primary-100 mt-1">Rejoignez la plateforme Ali-Kamer dès aujourd'hui</p>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="p-6 sm:p-8">
                    <form action="{{ route('register') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Nom complet -->
                        <div>
                            <label for="name" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Nom complet
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                                placeholder="Jean Dupont"
                                class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                @error('name') border-danger focus:ring-danger @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/20 @enderror
                                focus:outline-none">

                            @error('name')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Adresse e-mail
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                placeholder="nom@email.com"
                                class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                @error('email') border-danger focus:ring-danger @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/20 @enderror
                                focus:outline-none">

                            @error('email')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Téléphone
                            </label>
                            <div class="flex rounded-2xl border border-slate-200 overflow-hidden bg-slate-50/50 focus-within:border-primary-600 focus-within:ring-4 focus-within:ring-primary-100 transition">
                                <div class="px-4 flex items-center bg-slate-100/80 border-r border-slate-200 text-slate-600 font-semibold text-xs">
                                    🇨🇲 +237
                                </div>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    placeholder="6 XX XX XX XX"
                                    class="w-full bg-transparent px-4 py-3.5 text-sm focus:outline-none">
                            </div>

                            @error('phone')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label for="password" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Mot de passe
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required placeholder="Minimum 8 caractères"
                                    class="w-full rounded-2xl border px-4 py-3.5 pr-12 text-sm transition bg-slate-50/50
                                    @error('password') border-danger focus:ring-danger @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/20 @enderror
                                    focus:outline-none">
                                <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary-600 text-sm">
                                    👁
                                </button>
                            </div>

                            @error('password')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div>
                            <label for="password_confirmation" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Confirmation
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Confirmez le mot de passe"
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 pr-12 text-sm transition bg-slate-50/50 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/20 focus:outline-none">
                                <button type="button" id="togglePasswordConfirmation" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary-600 text-sm">
                                    👁
                                </button>
                            </div>
                        </div>

                        <!-- Choix du Rôle -->
                        <div>
                            <label class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Rejoindre en tant que :
                            </label>
                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <label class="flex items-center gap-2.5 p-3.5 border border-slate-200 rounded-2xl cursor-pointer bg-slate-50/50 transition hover:border-primary-500 has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50/60 has-[:checked]:ring-2 has-[:checked]:ring-primary-600">
                                    <input type="radio" name="role" value="buyer" class="h-4 w-4 text-primary-600 focus:ring-primary-500 cursor-pointer" {{ old('role', 'buyer') == 'buyer' ? 'checked' : '' }}>
                                    <span class="text-xs font-bold text-slate-700">Acheteur</span>
                                </label>

                                <label class="flex items-center gap-2.5 p-3.5 border border-slate-200 rounded-2xl cursor-pointer bg-slate-50/50 transition hover:border-primary-500 has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50/60 has-[:checked]:ring-2 has-[:checked]:ring-primary-600">
                                    <input type="radio" name="role" value="seller" class="h-4 w-4 text-primary-600 focus:ring-primary-500 cursor-pointer" {{ old('role') == 'seller' ? 'checked' : '' }}>
                                    <span class="text-xs font-bold text-slate-700">Vendeur</span>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bouton Submit -->
                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-primary-700 to-primary-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary-200 transition hover:from-primary-800 hover:to-primary-700 active:scale-[0.99] mt-3">
                            Créer mon compte
                        </button>
                    </form>

                    <!-- Footer Connexion -->
                    <div class="mt-6 border-t border-slate-100 pt-4 text-center text-xs text-slate-600 flex items-center justify-center gap-1.5">
                        <span>Vous avez déjà un compte ?</span>
                        <a href="{{ route('login.show') }}" class="font-bold text-primary-600 hover:text-primary-800 hover:underline">
                            Se connecter
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script pour masquer/afficher les mots de passe --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupToggle(buttonId, inputId) {
                const btn = document.getElementById(buttonId);
                const input = document.getElementById(inputId);
                if (btn && input) {
                    btn.addEventListener('click', () => {
                        input.type = input.type === 'password' ? 'text' : 'password';
                    });
                }
            }
            setupToggle('togglePassword', 'password');
            setupToggle('togglePasswordConfirmation', 'password_confirmation');
        });
    </script>

</body>
</html>