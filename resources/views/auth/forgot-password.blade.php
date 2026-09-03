<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mot de passe oublié — {{ config('app.name', 'Ali-Kamer') }}</title>

    {{-- Importation CSS/JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50/50">

    <!-- Conteneur principal plein écran -->
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">

            {{-- Alerte de succès --}}
            @if (session('status'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs sm:text-sm text-emerald-700 shadow-sm font-medium">
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Alerte d'erreur --}}
            @if (session('fail') || session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs sm:text-sm text-rose-700 shadow-sm font-medium">
                    <svg class="h-5 w-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('fail') ?? session('error') }}</span>
                </div>
            @endif

            {{-- Carte Principale --}}
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">

                <!-- En-tête aux couleurs Ali-Kamer -->
                <div class="bg-gradient-to-br from-[#004d28] via-[#006837] to-[#046A38] px-6 pt-8 pb-6 text-center relative overflow-hidden text-white">
                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute -bottom-12 -left-12 w-32 h-32 rounded-full bg-[#FFC20E]/20 blur-xl"></div>

                    <div class="relative z-10">
                        <div class="inline-flex items-center justify-center p-2.5 bg-white rounded-2xl shadow-lg mb-3">
                            <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-12 w-auto object-contain">
                        </div>
                        <h1 class="text-2xl font-black tracking-tight text-white">Mot de passe oublié ?</h1>
                        <p class="text-xs text-emerald-100/90 mt-1">Pas d'inquiétude, nous allons vous aider à récupérer l'accès</p>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="p-6 sm:p-8">

                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 text-center">
                        Saisissez l'adresse e-mail associée à votre compte. Nous vous enverrons un lien sécurisé pour créer un nouveau mot de passe.
                    </p>

                    <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Champ Email -->
                        <div>
                            <label for="email" class="block mb-1.5 text-xs font-bold uppercase tracking-wider text-slate-700">
                                Adresse e-mail <span class="text-red-500">*</span>
                            </label>

                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>

                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                    placeholder="exemple@email.cm"
                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border text-sm transition bg-slate-50/50 text-slate-800 placeholder-slate-400
                                    @error('email') border-rose-500 focus:ring-rose-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror
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
                            class="w-full rounded-2xl bg-[#006837] hover:bg-[#004d28] py-3.5 text-sm font-bold text-white shadow-lg shadow-[#006837]/20 transition active:scale-[0.99] flex items-center justify-center gap-2">
                            <span>Envoyer le lien de réinitialisation</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Lien retour -->
                    <div class="mt-6 border-t border-slate-100 pt-5 text-center">
                        <a href="{{ route('login.show') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#006837] hover:text-[#004d28] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Retour à la page de connexion</span>
                        </a>
                    </div>

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