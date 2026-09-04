<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte acheteur — {{ config('app.name', 'Ali-Kamer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-slate-800 bg-white overflow-hidden">

    <div class="h-screen w-full flex">

        <!-- ================= COLONNE GAUCHE : FORMULAIRE ACHETEUR ================= -->
        <div
            class="w-full lg:w-1/2 h-full flex flex-col justify-between p-6 sm:p-8 lg:p-10 xl:p-12 bg-white overflow-hidden min-h-0">

            <!-- 1. En-tête / Logo -->
            <div class="flex items-center justify-between shrink-0 mb-2">
                <a href="/" class="group flex items-center gap-3 transition-transform active:scale-95">
                    <div
                        class="p-2 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm group-hover:border-slate-200">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo"
                            class="h-10 sm:h-11 w-auto object-contain">
                    </div>
                </a>
                <span
                    class="text-xs font-extrabold tracking-wider text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full uppercase">FR</span>
            </div>

            <!-- 2. Section Principale / Formulaire -->
            <div class="my-auto max-w-md w-full mx-auto py-1">

                <!-- Titre & Sous-titre -->
                <div class="mb-4 sm:mb-5">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Créer un compte acheteur
                    </h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Gratuit et sans engagement sur Ali-Kamer.</p>
                </div>

                <!-- Messages d'Alerte -->
                @if (session('fail'))
                    <div
                        class="mb-3 rounded-2xl border border-red-200 bg-red-50/90 p-3 text-xs sm:text-sm text-red-700 shadow-sm flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('fail') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-3 rounded-2xl border border-red-200 bg-red-50/90 p-3 text-xs text-red-700 shadow-sm">
                        <p class="font-bold text-xs text-red-800 mb-0.5">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Authentification Sociale en 2 Colonnes -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <a href="{{ route('social.redirect', [
                        'provider' => 'google',
                        'role' => 'buyer',
                    ]) }}"
                        class="flex items-center justify-center gap-2.5 rounded-2xl border border-slate-200 bg-white py-2.5 px-3 text-xs sm:text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M21.35 12.23c0-.79-.07-1.55-.2-2.28H12v4.31h5.23a4.47 4.47 0 0 1-1.94 2.94v2.45h3.14c1.84-1.69 2.92-4.18 2.92-7.42z" />
                            <path fill="#34A853"
                                d="M12 21.5c2.63 0 4.84-.87 6.45-2.35l-3.14-2.45c-.87.58-1.98.92-3.31.92-2.54 0-4.69-1.72-5.46-4.03H3.3v2.53A9.74 9.74 0 0 0 12 21.5z" />
                            <path fill="#FBBC05"
                                d="M6.54 13.59A5.85 5.85 0 0 1 6.23 12c0-.55.1-1.09.31-1.59V7.88H3.3A9.74 9.74 0 0 0 2.25 12c0 1.57.38 3.05 1.05 4.12l3.24-2.53z" />
                            <path fill="#EA4335"
                                d="M12 6.38c1.43 0 2.71.49 3.72 1.45l2.79-2.79C16.84 3.43 14.63 2.5 12 2.5a9.74 9.74 0 0 0-8.7 5.38l3.24 2.53C7.31 8.1 9.46 6.38 12 6.38z" />
                        </svg>
                        <span>continuer avec Google</span>
                    </a>

                    <a href="{{route('facebook.register', ['role' => 'buyer'])}}"
                        class="flex items-center justify-center gap-2.5 rounded-2xl border border-slate-200 bg-white py-2.5 px-3 text-xs sm:text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5 text-[#1877F2] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.02 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.03 1.79-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.88v2.27h3.34l-.53 3.49h-2.81V24C19.61 23.09 24 18.09 24 12.07z" />
                        </svg>
                        <span>continuer avec Facebook</span>
                    </a>
                </div>

                <!-- Séparateur -->
                <div class="relative flex items-center my-3">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span
                        class="mx-3 text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 shrink-0">
                        ou avec votre e-mail
                    </span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Formulaire Inscription -->
                <form method="POST" action="{{ route('register.buyer.post') }}" class="space-y-3 sm:space-y-3.5">
                    @csrf

                    <!-- Nom complet -->
                    <div>
                        <label for="name"
                            class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nom complet <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Jean Dupont"
                            class="w-full rounded-2xl border px-4 py-2.5 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                            @error('name') border-red-500 focus:ring-2 focus:ring-red-500/10 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                    </div>

                    <!-- Téléphone OM / MoMo -->
                    <div>
                        <label for="phone_momo"
                            class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Numéro OM / MoMo <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50 hover:bg-white focus-within:bg-white
                            @error('phone_momo') border-red-500 @else border-slate-200 focus-within:border-[#006837] focus-within:ring-4 focus-within:ring-[#006837]/10 @enderror">
                            <div
                                class="px-3.5 flex items-center bg-slate-100 text-slate-600 text-xs font-bold border-r border-slate-200 shrink-0">
                                🇨🇲 +237
                            </div>
                            <input id="phone_momo" type="tel" name="phone_momo" value="{{ old('phone_momo') }}"
                                required placeholder="6 XX XX XX XX" maxlength="9"
                                class="w-full bg-transparent px-4 py-2.5 text-sm focus:outline-none">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Adresse e-mail <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            placeholder="vous@exemple.cm"
                            class="w-full rounded-2xl border px-4 py-2.5 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                            @error('email') border-red-500 focus:ring-2 focus:ring-red-500/10 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                    </div>

                    <!-- Mot de passe + Confirmation (Grid 2 Colonnes) -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="password"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" type="password" name="password" placeholder="Min. 8 car." required
                                    class="w-full rounded-2xl border px-3.5 py-2.5 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white pr-9
                                    @error('password') border-red-500 focus:ring-2 focus:ring-red-500/10 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                                <button type="button" id="togglePassword"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#006837] text-xs p-1">
                                    👁
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Confirmer <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="Répéter" required
                                    class="w-full rounded-2xl border border-slate-200 px-3.5 py-2.5 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white pr-9 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none">
                                <button type="button" id="togglePasswordConfirmation"
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#006837] text-xs p-1">
                                    👁
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Conditions -->
                    <p class="text-[11px] text-slate-500 leading-tight pt-0.5">
                        En créant un compte, vous acceptez nos <a href="#"
                            class="text-[#006837] underline font-bold">conditions</a> et notre <a href="#"
                            class="text-[#006837] underline font-bold">politique de confidentialité</a>.
                    </p>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-[#006837] py-3 text-sm font-bold text-white shadow-lg shadow-[#006837]/25 transition-all hover:bg-[#00522b] active:scale-[0.99] flex items-center justify-center gap-2 group mt-2">
                        <span>Créer mon compte</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Lien Se Connecter -->
                <p class="mt-4 text-center text-xs sm:text-sm text-slate-500 font-medium">
                    Déjà un compte ?
                    <a href="{{ route('login.show') }}" class="font-bold text-[#006837] hover:underline">Se
                        connecter</a>
                </p>
            </div>

            <!-- 3. Mini Footer / Navigation -->
            <div
                class="shrink-0 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 mt-1">
                <a href="{{ route('buyer.home') }}"
                    class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-[#006837] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Accueil</span>
                </a>
                <span>&copy; {{ date('Y') }} Ali-Kamer</span>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : BANNIÈRE AVEC LOGO INTEGRÉ ================= -->
        <div
            class="hidden lg:flex w-1/2 h-full bg-[#004d28] text-white p-10 xl:p-14 flex-col justify-between relative overflow-hidden shrink-0">

            <!-- Cercles décoratifs d'arrière-plan -->
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/5 blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-[#FFC20E]/10 blur-3xl pointer-events-none">
            </div>

            <!-- Logo Ali-Kamer géant en arrière-plan (Filigrane) -->
            <div class="absolute -right-16 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                <img src="{{ asset('images/afrique.png') }}" alt="" class="w-[500px] h-auto object-contain">
            </div>

            <div class="relative z-10">
                <!-- Badge & Grand Logo Ali-Kamer en surbrillance -->
                <div class="flex items-center justify-between">
                    <span
                        class="inline-block text-xs font-bold tracking-widest uppercase text-[#FFC20E] bg-white/10 px-3.5 py-1.5 rounded-full backdrop-blur-md">
                        Marketplace N°1 au Cameroun
                    </span>
                    <div class="p-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 shadow-xl">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo"
                            class="h-10 w-auto object-contain drop-shadow">
                    </div>
                </div>

                <h2 class="text-3xl xl:text-4xl font-black mt-6 leading-tight tracking-tight">
                    Achetez en toute sérénité <br><span class="text-[#FFC20E]">sans stress.</span>
                </h2>
                <p class="text-sm text-emerald-100/90 mt-3 leading-relaxed max-w-md font-normal">
                    Accédez aux meilleurs produits des vendeurs de tout le Cameroun avec des garanties de paiement et de
                    livraison.
                </p>
            </div>

            <!-- Avantages spécial Acheteur -->
            <div class="relative z-10 space-y-5 my-auto max-w-md">
                <div class="flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        🔒
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Paiements sécurisés (MoMo / OM)</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-snug">Vos fonds sont protégés et ne sont
                            libérés qu'après réception de votre commande.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        📦
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Livraison interurbaine garantie</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-snug">Suivez votre colis depuis le départ du
                            vendeur jusqu'à l'agence de retrait.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div
                        class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        ⭐
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Boutiques certifiées & avis vérifiés</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-snug">Consultez les notes des autres
                            acheteurs avant de passer commande.</p>
                    </div>
                </div>
            </div>

            <!-- Citation -->
            <div class="relative z-10 pt-5 border-t border-white/10">
                <p class="text-xs sm:text-sm text-emerald-100/90 italic leading-relaxed">
                    « Une plateforme fiable pour commander à Douala, Yaoundé ou Bafoussam sans se déplacer. »
                </p>
                <p class="text-xs font-bold text-[#FFC20E] mt-2">— Acheteur vérifié sur Ali-Kamer</p>
            </div>

        </div>

    </div>

    <!-- Script Masquer/Afficher le mot de passe -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

</body>

</html>
