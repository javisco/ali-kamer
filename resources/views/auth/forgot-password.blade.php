<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mot de passe oublié — {{ config('app.name', 'Ali-Kamer') }}</title>

    {{-- Tailwind CSS via CDN pour l'autonomie totale --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Si vous préférez utiliser votre build Vite local, décommentez la ligne ci-dessous --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="h-full font-sans antialiased text-slate-800">

    <!-- Conteneur principal plein écran -->
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-slate-50/60">
        <div class="w-full max-w-md">

            {{-- Alerte de succès --}}
            @if (session('status'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm">
                    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Alerte d'erreur --}}
            @if (session('fail'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 shadow-sm">
                    <svg class="h-5 w-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('fail') }}</span>
                </div>
            @endif

            {{-- Carte Principale --}}
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

                <!-- En-tête Gradient Bleu -->
                <div class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 px-6 pt-8 pb-6 text-center relative overflow-hidden">
                    {{-- Formes décoratives en arrière-plan --}}
                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute -bottom-12 -left-12 w-32 h-32 rounded-full bg-blue-300/10 blur-xl"></div>

                    <div class="relative z-10">
                        <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-xl mb-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Ali-Kamer Logo" class="h-14 w-auto object-contain">
                        </div>
                        <h1 class="text-2xl font-black tracking-tight text-white">Mot de passe oublié ?</h1>
                        <p class="text-xs text-blue-100 mt-1">Pas de soucis, nous allons vous aider à récupérer l'accès</p>
                    </div>
                </div>

                <!-- Corps de la carte / Formulaire -->
                <div class="p-6 sm:p-8">

                    <p class="text-xs text-slate-500 leading-relaxed mb-6 text-center">
                        Saisissez l'adresse e-mail associée à votre compte. Nous vous enverrons un lien de réinitialisation sécurisé.
                    </p>

                    <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Champ Email -->
                        <div>
                            <label for="email" class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Adresse e-mail
                            </label>

                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>

                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                    placeholder="exemple@email.com"
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border text-sm transition bg-slate-50/50 text-slate-800 placeholder-slate-400
                                    @error('email') border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror
                                    focus:outline-none">
                            </div>

                            @error('email')
                                <p class="mt-1.5 text-xs text-rose-600 font-medium">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Bouton d'action -->
                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-800 to-blue-600 hover:from-blue-900 hover:to-blue-700 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/20 transition active:scale-[0.99]">
                            Envoyer le lien de réinitialisation
                        </button>
                    </form>

                    <!-- Footer / Lien vers connexion -->
                    <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                        <a href="{{ route('login.show') }}" class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Retour à la page de connexion</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>
</html>