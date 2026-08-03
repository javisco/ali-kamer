@extends('base')

@section('title', 'Connexion')

@section('content')
<!-- Conteneur principal -->
<div class="min-h-[85vh] flex items-center justify-center px-4 py-8 bg-slate-50/50">
    <div class="w-full max-w-md">

        {{-- Alerte d'erreur --}}
        @if (session('fail'))
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('fail') }}</span>
            </div>
        @endif

        {{-- Alerte de succès --}}
        @if (session('success'))
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

            <!-- En-tête avec Logo -->
            <div class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 px-6 pt-8 pb-6 text-center relative overflow-hidden">
                {{-- Décor de fond subtil --}}
                <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 rounded-full bg-cyan-300/10 blur-xl"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-xl mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Ali-Kamer Logo" class="h-14 w-auto object-contain">
                    </div>
                    <h1 class="text-2xl font-black tracking-tight text-white">Bienvenue !</h1>
                    <p class="text-xs text-blue-100 mt-1">Connectez-vous à votre espace personnel Ali-Kamer</p>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="p-6 sm:p-8">
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Adresse e-mail
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="exemple@email.com"
                            class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                            @error('email') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                            focus:outline-none">

                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-medium">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Mot de passe
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                Oublié ?
                            </a>
                        </div>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                            @error('password') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                            focus:outline-none">

                        @error('password')
                            <p class="mt-1 text-xs text-red-600 font-medium">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Options (Se souvenir) -->
                    <div class="flex items-center pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="text-xs text-slate-600 font-medium">
                                Se souvenir de moi
                            </span>
                        </label>
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:from-blue-800 hover:to-blue-700 active:scale-[0.99] mt-2">
                        Se connecter
                    </button>
                </form>

                <!-- Footer Inscription -->
                <div class="mt-6 border-t border-slate-100 pt-4 text-center text-xs text-slate-600 flex items-center justify-center gap-1.5">
                    <span>Vous n'avez pas de compte ?</span>
                    <a href="{{ route('register.show') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                        Créer un compte
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection