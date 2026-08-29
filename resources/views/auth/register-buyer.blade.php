<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte acheteur — Ali-Kamer</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full font-sans antialiased text-slate-800">

    <!-- Conteneur principal -->
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-slate-50/50">
        <div class="w-full max-w-md">

            {{-- Alerte d'erreur globale --}}
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                    <svg class="h-5 w-5 shrink-0 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-bold">Veuillez corriger les erreurs ci-dessous :</p>
                        <ul class="list-disc list-inside text-xs mt-1 space-y-1 text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Carte Principale --}}
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

                <!-- En-tête avec Logo et Infos intégrées -->
                <div class="bg-gradient-to-br from-blue-900 via-blue-700 to-blue-600 px-6 pt-8 pb-6 text-center relative overflow-hidden">
                    {{-- Décoration de fond --}}
                    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute -bottom-12 -left-12 w-32 h-32 rounded-full bg-cyan-300/10 blur-xl"></div>

                    <div class="relative z-10">
                        <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-xl mb-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Ali-Kamer Logo" class="h-14 w-auto object-contain">
                        </div>
                        <h1 class="text-2xl font-black tracking-tight text-white">Créer un compte acheteur</h1>
                        <p class="text-xs text-blue-100 mt-1">Gratuit et sans engagement sur Ali-Kamer</p>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('register.buyer.post') }}" class="space-y-4">
                        @csrf

                        {{-- Nom --}}
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Jean Dupont"
                                class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                @error('name') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror focus:outline-none">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Téléphone OM/MoMo --}}
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Numéro OM / MoMo <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50
                                @error('phone_momo') border-red-500 @else border-slate-200 focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100 @enderror">
                                <div class="px-3.5 flex items-center bg-slate-100 text-slate-600 text-xs font-semibold border-r border-slate-200 shrink-0">
                                    🇨🇲 +237
                                </div>
                                <input type="tel" name="phone_momo" value="{{ old('phone_momo') }}" required placeholder="6 XX XX XX XX" maxlength="9"
                                    class="w-full bg-transparent px-4 py-3.5 text-sm focus:outline-none">
                            </div>
                            @error('phone_momo')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Adresse e-mail <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="vous@exemple.cm"
                                class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                @error('email') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror focus:outline-none">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mot de passe --}}
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required placeholder="Minimum 8 caractères"
                                class="w-full rounded-2xl border px-4 py-3.5 text-sm transition bg-slate-50/50
                                @error('password') border-red-500 focus:ring-red-500 @else border-slate-200 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @enderror focus:outline-none">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmation --}}
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                                Confirmer le mot de passe <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required placeholder="Répétez votre mot de passe"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-sm transition bg-slate-50/50 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 focus:outline-none">
                        </div>

                        {{-- Bouton Submit --}}
                        <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-700 to-blue-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition hover:from-blue-800 hover:to-blue-700 active:scale-[0.99] mt-2 flex items-center justify-center gap-2">
                            <span>Créer mon compte</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>

                    <!-- Footer Connexion -->
                    <div class="mt-6 border-t border-slate-100 pt-4 text-center text-xs text-slate-600 flex items-center justify-center gap-1.5">
                        <span>Déjà un compte ?</span>
                        <a href="{{ route('login.show') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                            Se connecter
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bouton Retour à l'accueil en bas -->
            <div class="mt-6 text-center">
                <a href="{{ route('buyer.home') }}" 
                   class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Retour à l'accueil</span>
                </a>
            </div>

        </div>
    </div>

</body>

</html>