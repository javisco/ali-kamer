<!DOCTYPE html>
<html lang="fr" class="bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte vendeur — {{ config('app.name', 'Ali-Kamer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-800 bg-white min-h-screen">

    <!-- Conteneur global unique -->
    <div class="w-full min-h-screen flex flex-col lg:flex-row items-stretch">

        <!-- ================= COLONNE GAUCHE : FORMULAIRE VENDEUR ================= -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-10 xl:p-14 bg-white">

            <!-- 1. En-tête / Logo -->
            <div class="flex items-center justify-between shrink-0 mb-8">
                <a href="/" class="group flex items-center gap-3 transition-transform active:scale-95">
                    <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm group-hover:border-slate-200">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-10 sm:h-12 w-auto object-contain">
                    </div>
                </a>
                <span class="text-xs font-extrabold tracking-wider text-slate-600 bg-slate-100 px-3.5 py-1.5 rounded-full uppercase">FR</span>
            </div>

            <!-- 2. Section Principale / Formulaire -->
            <div class="max-w-lg w-full mx-auto my-auto">
                
                <!-- Titre & Sous-titre -->
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Devenir Vendeur Partner</h1>
                    <p class="text-sm text-slate-500 mt-1.5 font-medium">Ouvrez votre boutique et vendez vos produits dans tout le Cameroun.</p>
                </div>

                <!-- Messages d'Alerte -->
                @if (session('fail'))
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50/90 p-4 text-sm text-red-700 shadow-sm flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('fail') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50/90 p-4 text-sm text-red-700 shadow-sm">
                        <p class="font-bold text-red-800 mb-1.5">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Authentification Sociale Vendeur (2 Colonnes) -->
                <div class="grid grid-cols-2 gap-3.5 mb-5">
                    <a href="{{ route('social.redirect', ['provider' => 'google', 'role' => 'seller']) }}"
                        class="flex items-center justify-center gap-2.5 rounded-2xl border border-slate-200 bg-white py-3 px-3 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M21.35 12.23c0-.79-.07-1.55-.2-2.28H12v4.31h5.23a4.47 4.47 0 0 1-1.94 2.94v2.45h3.14c1.84-1.69 2.92-4.18 2.92-7.42z" />
                            <path fill="#34A853" d="M12 21.5c2.63 0 4.84-.87 6.45-2.35l-3.14-2.45c-.87.58-1.98.92-3.31.92-2.54 0-4.69-1.72-5.46-4.03H3.3v2.53A9.74 9.74 0 0 0 12 21.5z" />
                            <path fill="#FBBC05" d="M6.54 13.59A5.85 5.85 0 0 1 6.23 12c0-.55.1-1.09.31-1.59V7.88H3.3A9.74 9.74 0 0 0 2.25 12c0 1.57.38 3.05 1.05 4.12l3.24-2.53z" />
                            <path fill="#EA4335" d="M12 6.38c1.43 0 2.71.49 3.72 1.45l2.79-2.79C16.84 3.43 14.63 2.5 12 2.5a9.74 9.74 0 0 0-8.7 5.38l3.24 2.53C7.31 8.1 9.46 6.38 12 6.38z" />
                        </svg>
                        <span>Google</span>
                    </a>

                    <a href="{{route('facebook.register', ['role' => 'seller'])}}"
                        class="flex items-center justify-center gap-2.5 rounded-2xl border border-slate-200 bg-white py-3 px-3 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 text-[#1877F2] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.02 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.03 1.79-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.88v2.27h3.34l-.53 3.49h-2.81V24C19.61 23.09 24 18.09 24 12.07z" />
                        </svg>
                        <span>Facebook</span>
                    </a>
                </div>

                <!-- Séparateur -->
                <div class="relative flex items-center my-6">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="mx-4 text-xs font-bold uppercase tracking-wider text-slate-400 shrink-0">
                        ou par formulaire
                    </span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Formulaire Vendeur -->
                <form method="POST" action="{{ route('register.seller.post') }}" class="space-y-5">
                    @csrf

                    <!-- SECTION 1 : INFOS PERSONNELLES -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#006837]/10 text-xs font-extrabold text-[#006837]">1</span>
                            <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Informations personnelles</h2>
                        </div>

                        <!-- Nom complet -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Jean Dupont"
                                class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                @error('name') border-red-500 focus:ring-4 focus:ring-red-500/10 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                        </div>

                        <!-- Numéro Mobile Money + Choix Opérateur -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Numéro Mobile Money <span class="text-red-500">*</span>
                                <span class="text-[#006837] font-semibold normal-case text-xs">(pour vos retraits)</span>
                            </label>

                            <!-- Sélection Opérateur -->
                            <div class="grid grid-cols-2 gap-3 mb-2.5">
                                <label class="flex items-center gap-2.5 border-2 rounded-2xl p-2.5 cursor-pointer transition-all
                                    {{ old('momo_operator') === 'mtn' ? 'border-yellow-400 bg-yellow-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="mtn" {{ old('momo_operator') === 'mtn' ? 'checked' : '' }} required class="accent-[#006837]">
                                    <span class="text-xs font-bold text-yellow-700">MTN MoMo</span>
                                </label>
                                <label class="flex items-center gap-2.5 border-2 rounded-2xl p-2.5 cursor-pointer transition-all
                                    {{ old('momo_operator') === 'orange' ? 'border-orange-400 bg-orange-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="orange" {{ old('momo_operator') === 'orange' ? 'checked' : '' }} class="accent-[#006837]">
                                    <span class="text-xs font-bold text-orange-600">Orange Money</span>
                                </label>
                            </div>

                            <!-- Champ Téléphone MoMo -->
                            <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50 hover:bg-white focus-within:bg-white
                                @error('phone_momo') border-red-500 @else border-slate-200 focus-within:border-[#006837] focus-within:ring-4 focus-within:ring-[#006837]/10 @enderror">
                                <div class="px-3.5 flex items-center bg-slate-100 text-slate-600 text-xs font-bold border-r border-slate-200 shrink-0">
                                    🇨🇲 +237
                                </div>
                                <input type="tel" name="phone_momo" value="{{ old('phone_momo') }}" required placeholder="6 XX XX XX XX" maxlength="9"
                                    class="w-full bg-transparent px-4 py-3 text-sm focus:outline-none">
                            </div>
                        </div>

                        <!-- Téléphone de contact & Email -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Contact <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                                </label>
                                <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50 hover:bg-white focus-within:bg-white border-slate-200 focus-within:border-[#006837]">
                                    <div class="px-3 flex items-center bg-slate-100 text-slate-600 text-xs font-bold border-r border-slate-200 shrink-0">
                                        +237
                                    </div>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="6 XX XX XX XX" maxlength="9"
                                        class="w-full bg-transparent px-3.5 py-3 text-sm focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Adresse e-mail <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="vous@exemple.cm"
                                    class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                    @error('email') border-red-500 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                            </div>
                        </div>

                        <!-- Mot de passe + Confirmation -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" required placeholder="Min. 8 car."
                                    class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                    @error('password') border-red-500 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Confirmer <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" required placeholder="Répéter"
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2 : INFOS BOUTIQUE -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#006837]/10 text-xs font-extrabold text-[#006837]">2</span>
                            <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Votre Boutique</h2>
                        </div>

                        <!-- Nom boutique -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nom de la boutique <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shop_name" value="{{ old('shop_name') }}" required placeholder="Ex: Tech Shop Douala"
                                class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                @error('shop_name') border-red-500 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                        </div>

                        <!-- Ville + Catégorie -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Ville <span class="text-red-500">*</span>
                                </label>
                                <select name="city" required
                                    class="w-full rounded-2xl border border-slate-200 px-3.5 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none">
                                    <option value="">-- Choisir --</option>
                                    @foreach (['Douala', 'Yaoundé', 'Bafoussam', 'Bamenda', 'Buea', 'Limbé', 'Garoua', 'Maroua', 'Ngaoundéré', 'Bertoua', 'Ebolowa', 'Kribi'] as $city)
                                        <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select name="category" required
                                    class="w-full rounded-2xl border border-slate-200 px-3.5 py-3 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none">
                                    <option value="">-- Choisir --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->slug }}" {{ old('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Description <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                            </label>
                            <textarea name="description" rows="3" maxlength="500" placeholder="Décrivez votre boutique..."
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Note KYC -->
                    <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-4 text-xs text-amber-900 shadow-sm">
                        <p class="font-bold mb-1 flex items-center gap-1.5 text-amber-800">📋 Étape suivante : Validation KYC</p>
                        <p class="leading-relaxed text-amber-800/90 text-xs">
                            Vous soumettrez une pièce d'identité après création. Activation sous 24h à 48h.
                        </p>
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-[#006837] py-4 text-sm font-bold text-white shadow-lg shadow-[#006837]/25 transition-all hover:bg-[#00522b] active:scale-[0.99] flex items-center justify-center gap-2 group mt-4">
                        <span>Créer mon compte vendeur</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Lien Se Connecter -->
                <p class="mt-6 text-center text-sm text-slate-500 font-medium">
                    Déjà un compte ?
                    <a href="{{ route('login.show') }}" class="font-bold text-[#006837] hover:underline">Se connecter</a>
                </p>
            </div>

            <!-- 3. Mini Footer -->
            <div class="shrink-0 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 mt-8">
                <a href="{{ route('register.show') }}" class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-[#006837] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Choix des comptes</span>
                </a>
                <span>&copy; {{ date('Y') }} Ali-Kamer</span>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : BANNIÈRE ÉTENDUE (S'AJUSTE À LA HAUTEUR) ================= -->
        <div class="hidden lg:flex w-1/2 bg-[#004d28] text-white p-10 xl:p-14 flex-col justify-between relative overflow-hidden shrink-0">

            <!-- Cercles décoratifs d'arrière-plan -->
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-[#FFC20E]/10 blur-3xl pointer-events-none"></div>

            <!-- Logo Ali-Kamer géant en arrière-plan -->
            <div class="absolute -right-16 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                <img src="{{ asset('images/afrique.png') }}" alt="" class="w-[500px] h-auto object-contain">
            </div>

            <div class="relative z-10 space-y-6">
                <!-- Badge & Logo -->
                <div class="flex items-center justify-between">
                    <span class="inline-block text-xs font-bold tracking-widest uppercase text-[#FFC20E] bg-white/10 px-3.5 py-1.5 rounded-full backdrop-blur-md">
                        Espace Vendeurs Partner
                    </span>
                    <div class="p-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 shadow-xl">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-10 w-auto object-contain drop-shadow">
                    </div>
                </div>

                <div>
                    <h2 class="text-3xl xl:text-4xl font-black leading-tight tracking-tight">
                        Développez vos ventes <br><span class="text-[#FFC20E]">partout au Cameroun.</span>
                    </h2>
                    <p class="text-sm text-emerald-100/90 mt-3 leading-relaxed font-normal">
                        Rejoignez la plus grande marketplace locale et touchez des milliers de clients à Douala, Yaoundé, Bafoussam et dans toutes les régions.
                    </p>
                </div>
            </div>

            <!-- BLOC NOUVEAU : STATISTIQUES VENDEURS (Agrandit la bannière proprement) -->
            <div class="relative z-10 my-8 grid grid-cols-3 gap-3 bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-md">
                <div class="text-center">
                    <p class="text-xl xl:text-2xl font-black text-[#FFC20E]">10K+</p>
                    <p class="text-[11px] text-emerald-100/80 mt-0.5">Acheteurs actifs</p>
                </div>
                <div class="text-center border-x border-white/10 px-2">
                    <p class="text-xl xl:text-2xl font-black text-white">100%</p>
                    <p class="text-[11px] text-emerald-100/80 mt-0.5">Paiements sécurisés</p>
                </div>
                <div class="text-center">
                    <p class="text-xl xl:text-2xl font-black text-[#FFC20E]">10</p>
                    <p class="text-[11px] text-emerald-100/80 mt-0.5">Régions couvertes</p>
                </div>
            </div>

            <!-- Avantages Vendeur -->
            <div class="relative z-10 space-y-6 my-auto">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-xl">
                        💸
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-white">Paiements directs MoMo & Orange Money</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-relaxed">Recevez l'argent de vos ventes directement sur votre compte Mobile Money dès livraison.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-xl">
                        🚚
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-white">Réseau de livraison partenaire</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-relaxed">Expédiez facilement vos commandes interurbaines via nos agences partenaires sans tracas.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-xl">
                        📈
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-white">Gestion simple & badge certifié</h3>
                        <p class="text-xs text-emerald-100/80 mt-1 leading-relaxed">Tableau de bord intuitif pour gérer vos stocks, commandes et obtenir votre badge vérifié (KYC).</p>
                    </div>
                </div>
            </div>

            <!-- BLOC NOUVEAU : ENGAGEMENT ALI-KAMER -->
            <div class="relative z-10 mt-8 space-y-4">
                <div class="p-4 rounded-2xl bg-emerald-950/40 border border-white/10 backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#FFC20E] mb-1">
                        <span>🛡️</span>
                        <span>Garantie Ali-Kamer Partner</span>
                    </div>
                    <p class="text-xs text-emerald-100/80 leading-relaxed">
                        Accompagnement personnalisé dès votre inscription et support vendeur disponible 7j/7 par WhatsApp.
                    </p>
                </div>

                <!-- Citation -->
                <div class="pt-4 border-t border-white/10">
                    <p class="text-xs sm:text-sm text-emerald-100/90 italic leading-relaxed">
                        « Ali-Kamer nous permet d'expédier nos produits depuis Douala et Yaoundé en toute sérénité. »
                    </p>
                    <p class="text-xs font-bold text-[#FFC20E] mt-2">— Vendeur partenaire à Bafoussam</p>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
