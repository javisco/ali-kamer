<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — {{ config('app.name', 'Ali-Kamer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 overflow-hidden">

    <div class="h-screen w-full flex">

        <!-- ================= COLONNE GAUCHE : FORMULAIRE DE CONNEXION ================= -->
        <div class="w-full lg:w-1/2 h-full flex flex-col justify-between p-6 sm:p-8 lg:p-10 xl:p-12 bg-white overflow-hidden min-h-0">

            <!-- 1. En-tête / Logo -->
            <div class="flex items-center justify-between shrink-0 mb-2">
                <a href="/" class="group flex items-center gap-3 transition-transform active:scale-95">
                    <div class="p-2 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm group-hover:border-slate-200">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-10 sm:h-11 w-auto object-contain">
                    </div>
                </a>
                <span class="text-xs font-extrabold tracking-wider text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full uppercase">FR</span>
            </div>

            <!-- 2. Section Principale / Formulaire (Adapté à la hauteur disponible) -->
            <div class="my-auto max-w-md w-full mx-auto py-1">
                
                <!-- Titre & Sous-titre -->
                <div class="mb-5 sm:mb-6">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Bon retour parmi nous !</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Connectez-vous à votre espace personnel Ali-Kamer.</p>
                </div>

                <!-- Messages d'Alerte (Succès / Erreur) -->
                @if (session('fail'))
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50/90 p-3.5 text-sm text-red-700 shadow-sm flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('fail') }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50/90 p-3.5 text-sm text-emerald-700 shadow-sm flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50/90 p-3.5 text-xs text-red-700 shadow-sm">
                        <p class="font-bold text-xs text-red-800 mb-1">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Authentification Sociale en 2 Colonnes -->
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <a href="{{ route('social.login', ['provider' => 'google']) }}"
                        class="flex items-center justify-center gap-2.5 rounded-2xl border border-slate-200 bg-white py-3 px-3 text-xs sm:text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M21.35 12.23c0-.79-.07-1.55-.2-2.28H12v4.31h5.23a4.47 4.47 0 0 1-1.94 2.94v2.45h3.14c1.84-1.69 2.92-4.18 2.92-7.42z" />
                            <path fill="#34A853" d="M12 21.5c2.63 0 4.84-.87 6.45-2.35l-3.14-2.45c-.87.58-1.98.92-3.31.92-2.54 0-4.69-1.72-5.46-4.03H3.3v2.53A9.74 9.74 0 0 0 12 21.5z" />
                            <path fill="#FBBC05" d="M6.54 13.59A5.85 5.85 0 0 1 6.23 12c0-.55.1-1.09.31-1.59V7.88H3.3A9.74 9.74 0 0 0 2.25 12c0 1.57.38 3.05 1.05 4.12l3.24-2.53z" />
                            <path fill="#EA4335" d="M12 6.38c1.43 0 2.71.49 3.72 1.45l2.79-2.79C16.84 3.43 14.63 2.5 12 2.5a9.74 9.74 0 0 0-8.7 5.38l3.24 2.53C7.31 8.1 9.46 6.38 12 6.38z" />
                        </svg>
                        <span>continuer avec Google</span>
                    </a>

                    <a href="{{route('facebook.login')}}"
                        class="flex items-center justify-center gap-2.5 rounded-2xl border border-slate-200 bg-white py-3 px-3 text-xs sm:text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5 text-[#1877F2] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.02 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.03 1.79-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.88v2.27h3.34l-.53 3.49h-2.81V24C19.61 23.09 24 18.09 24 12.07z" />
                        </svg>
                        <span>continuer avec Facebook</span>
                    </a>
                </div>

                <!-- Séparateur -->
                <div class="relative flex items-center my-4 sm:my-5">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="mx-3 text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 shrink-0">
                        ou avec votre e-mail
                    </span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Formulaire -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Adresse e-mail <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="exemple@email.com"
                            class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                            @error('email') border-red-500 focus:ring-2 focus:ring-red-500/10 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#006837] hover:underline">
                                Oublié ?
                            </a>
                        </div>
                        <div class="relative">
                            <input id="password" type="password" name="password" placeholder="••••••••" required
                                class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white pr-10
                                @error('password') border-red-500 focus:ring-2 focus:ring-red-500/10 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#006837] text-sm p-1">
                                👁
                            </button>
                        </div>
                    </div>

                    <!-- Options (Se souvenir de moi) -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-[#006837] focus:ring-[#006837] cursor-pointer accent-[#006837]">
                            <span class="text-xs sm:text-sm text-slate-600 font-medium">Se souvenir de moi</span>
                        </label>
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-[#006837] py-3.5 text-sm font-bold text-white shadow-lg shadow-[#006837]/25 transition-all hover:bg-[#00522b] active:scale-[0.99] flex items-center justify-center gap-2 group mt-2">
                        <span>Se connecter</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Lien Inscription -->
                <p class="mt-5 text-center text-xs sm:text-sm text-slate-500 font-medium">
                    Nouveau sur Ali-Kamer ?
                    <a href="{{ route('register.show') }}" class="font-bold text-[#006837] hover:underline">Créer un compte</a>
                </p>
            </div>

            <!-- 3. Mini Footer / Navigation -->
            <div class="shrink-0 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 mt-2">
                <a href="/" class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-[#006837] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Accueil</span>
                </a>
                <span>&copy; {{ date('Y') }} Ali-Kamer</span>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : BANNIÈRE VISUELLE ================= -->
        <div class="hidden lg:flex w-1/2 h-full bg-[#004d28] text-white p-10 xl:p-14 flex-col justify-between relative overflow-hidden shrink-0">

            <!-- Cercles décoratifs d'arrière-plan -->
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-[#FFC20E]/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <span class="inline-block text-xs font-bold tracking-widest uppercase text-[#FFC20E] bg-white/10 px-3.5 py-1.5 rounded-full backdrop-blur-md">
                    Plateforme 100% Sécurisée
                </span>

                <h2 class="text-3xl xl:text-4xl font-black mt-5 leading-tight tracking-tight">
                    Retrouvez vos achats <br><span class="text-[#FFC20E]">et vos boutiques.</span>
                </h2>
                <p class="text-sm text-emerald-100/90 mt-3 leading-relaxed max-w-md font-normal">
                    Accédez à votre historique de commandes, suivez vos livraisons en cours et gérez votre profil en toute simplicité.
                </p>
            </div>

            <!-- Points Forts de la Connexion -->
            <div class="relative z-10 space-y-5 my-auto max-w-md">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        ⚡
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Accès rapide & sécurisé</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-snug">Connectez-vous en un clic avec vos identifiants ou via vos réseaux sociaux.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        📦
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Suivi en temps réel</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-snug">Gardez un œil sur l'acheminement de tous vos colis à travers le Cameroun.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        💬
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Assistance dédiée</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-snug">Notre support est disponible pour répondre à l'ensemble de vos questions.</p>
                    </div>
                </div>
            </div>

            <!-- Témoignage / Citation -->
            <div class="relative z-10 pt-5 border-t border-white/10">
                <p class="text-xs sm:text-sm text-emerald-100/90 italic leading-relaxed">
                    « L'expérience d'achat sur Ali-Kamer est fluide et mes informations de paiement sont toujours protégées. »
                </p>
                <p class="text-xs font-bold text-[#FFC20E] mt-2">— Utilisateur régulier sur Ali-Kamer</p>
            </div>

        </div>

    </div>

    <!-- Script Masquer/Afficher le mot de passe -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('togglePassword');
            const input = document.getElementById('password');
            if (btn && input) {
                btn.addEventListener('click', () => {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                });
            }
        });
    </script>

</body>

</html>