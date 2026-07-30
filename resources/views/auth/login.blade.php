@extends('base')

@section('title', 'Connexion')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">

        {{-- Alerte d'erreur --}}
        @if (session('fail'))
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('fail') }}</span>
            </div>
        @endif

        {{-- Alerte de succès --}}
        @if (session('success'))
            <div class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            <!-- En-tête -->
            <div class="bg-blue-600 px-6 py-7 text-center">
                <h1 class="text-3xl font-bold tracking-tight text-white">ALI-KAMER</h1>
                <p class="text-sm text-blue-100 mt-1">Connectez-vous à votre espace personnel</p>
            </div>

            <!-- Formulaire -->
            <div class="p-6 sm:p-8">
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 font-medium text-gray-700">
                            Adresse e-mail
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="exemple@email.com"
                            class="w-full rounded-xl border px-4 py-3 text-base shadow-sm transition
                            @error('email') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                            focus:outline-none focus:ring-2">

                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600 font-medium">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block mb-2 font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                            class="w-full rounded-xl border px-4 py-3 text-base shadow-sm transition
                            @error('password') border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 @enderror
                            focus:outline-none focus:ring-2">

                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600 font-medium">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Options (Se souvenir / Mdp oublié) -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="text-sm text-gray-600 font-medium">
                                Se souvenir de moi
                            </span>
                        </label>

                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 py-3.5 text-base font-semibold text-white shadow-md transition hover:bg-blue-700 active:scale-[0.99]">
                        Se connecter
                    </button>
                </form>

                <!-- Footer Inscription -->
                <div class="mt-6 border-t pt-5 text-center text-base text-gray-600 flex items-center justify-center gap-2">
                    <span>Vous n'avez pas de compte ?</span>
                    <a href="{{ route('register.show') }}" class="font-semibold text-blue-600 hover:underline">
                        Créer un compte
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection