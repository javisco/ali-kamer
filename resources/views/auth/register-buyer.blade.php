<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte acheteur — {{ config('app.name', 'Ali-Kamer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-white">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ================= COLONNE GAUCHE : FORMULAIRE ACHETEUR ================= -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-12 lg:px-16 xl:px-20">
            
            <!-- En-tête / Logo mis en valeur -->
            <div class="flex items-center justify-between mb-8">
                <a href="/" class="group inline-flex items-center gap-3 p-2.5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-center p-1.5 rounded-xl bg-slate-50">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-14 w-auto object-contain transition-transform group-hover:scale-105">
                    </div>
                </a>
                <span class="text-xs font-bold tracking-wider text-slate-400 bg-slate-100 px-3 py-1.5 rounded-full">FR</span>
            </div>

            <!-- Message d'erreur session / globale -->
            @if (session('fail'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
                    {{ session('fail') }}
                </div>
            @endif

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

            <!-- Formulaire Principal -->
            <div class="my-auto py-2">
                <div class="mb-6">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Créer un compte acheteur</h1>
                    <p class="text-sm text-slate-500 mt-1.5">Gratuit et sans engagement sur Ali-Kamer.</p>
                </div>

                <form method="POST" action="{{ route('register.buyer.post') }}" class="space-y-4">
                    @csrf

                    <!-- Nom complet -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nom complet <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Jean Dupont"
                            class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50
                            @error('name') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone OM/MoMo -->
                    <div>
                        <label for="phone_momo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Numéro OM / MoMo <span class="text-red-500">*</span>
                        </label>
                        <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50
                            @error('phone_momo') border-red-500 @else border-slate-200 focus-within:border-[#006837] focus-within:ring-4 focus-within:ring-[#006837]/10 @enderror">
                            <div class="px-3.5 flex items-center bg-slate-100 text-slate-600 text-xs font-semibold border-r border-slate-200 shrink-0">
                                🇨🇲 +237
                            </div>
                            <input id="phone_momo" type="tel" name="phone_momo" value="{{ old('phone_momo') }}" required placeholder="6 XX XX XX XX" maxlength="9"
                                class="w-full bg-transparent px-4 py-3 text-sm focus:outline-none">
                        </div>
                        @error('phone_momo')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Adresse e-mail <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="vous@exemple.cm"
                            class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50
                            @error('email') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe + Confirmation -->
                    <div class="grid sm:grid-cols-2 gap-4">
                        <!-- Mot de passe -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Mot de passe <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" type="password" name="password" placeholder="Minimum 8 caractères" required
                                    class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50 pr-10
                                    @error('password') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                                <button type="button" id="togglePassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#006837] text-xs">
                                    👁
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Confirmer <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Répétez le mot de passe" required
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm transition bg-slate-50/50 pr-10 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none">
                                <button type="button" id="togglePasswordConfirmation" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#006837] text-xs">
                                    👁
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Conditions -->
                    <p class="text-[11px] text-slate-500 leading-normal pt-1">
                        En créant un compte, vous acceptez nos <a href="#" class="text-[#006837] underline font-medium">conditions d'utilisation</a> et notre <a href="#" class="text-[#006837] underline">politique de confidentialité</a>.
                    </p>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-[#006837] py-3.5 text-sm font-bold text-white shadow-lg shadow-[#006837]/20 transition hover:bg-[#004d28] active:scale-[0.99] flex items-center justify-center gap-2 mt-2">
                        <span>Créer mon compte</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Lien Se connecter -->
                <p class="mt-6 text-center text-xs text-slate-600">
                    Déjà un compte ?
                    <a href="{{ route('login.show') }}" class="font-bold text-[#006837] hover:text-[#004d28] hover:underline">
                        Se connecter
                    </a>
                </p>
            </div>

            <!-- Mini Footer / Retour à l'accueil -->
            <div class="text-[11px] text-slate-400 flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('buyer.home') }}" class="inline-flex items-center gap-1.5 font-semibold text-slate-500 hover:text-[#006837] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Retour à l'accueil</span>
                </a>
                <span>&copy; {{ date('Y') }} Ali-Kamer</span>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : BANNIÈRE D'INFORMATION ================= -->
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-[#004d28] via-[#006837] to-[#046A38] text-white p-12 xl:p-16 flex-col justify-between relative overflow-hidden">
            
            <!-- Cercles décoratifs d'arrière-plan -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-28 -left-24 w-96 h-96 rounded-full bg-[#FFC20E]/20 blur-3xl"></div>

            <div class="relative z-10">
                <span class="text-xs font-bold tracking-widest uppercase text-[#FFC20E] bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                    Marketplace N°1 au Cameroun
                </span>
                
                <h2 class="text-4xl xl:text-5xl font-black mt-6 leading-tight">
                    Achetez en toute sérénité <br><span class="text-[#FFC20E]">sans stress.</span>
                </h2>
                <p class="text-sm text-emerald-100/90 mt-4 leading-relaxed max-w-lg">
                    Accédez aux meilleurs produits des vendeurs de tout le Cameroun avec des garanties de paiement et de livraison.
                </p>
            </div>

            <!-- Avantages spécial Acheteur -->
            <div class="relative z-10 space-y-6 my-auto max-w-lg">
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        🔒
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Paiements sécurisés (MoMo / OM)</h3>
                        <p class="text-xs text-emerald-100/80 mt-0.5">Vos fonds sont protégés et ne sont libérés qu'après réception de votre commande.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        📦
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Livraison interurbaine garantie</h3>
                        <p class="text-xs text-emerald-100/80 mt-0.5">Suivez votre colis depuis le départ du vendeur jusqu'à l'agence de retrait.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        ⭐
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Boutiques certifiées & avis vérifiés</h3>
                        <p class="text-xs text-emerald-100/80 mt-0.5">Consultez les notes des autres acheteurs avant de passer commande.</p>
                    </div>
                </div>

            </div>

            <!-- Citation -->
            <div class="relative z-10 pt-6 border-t border-white/10">
                <p class="text-xs text-emerald-100 italic leading-relaxed">
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