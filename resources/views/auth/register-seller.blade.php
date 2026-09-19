<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte vendeur — {{ config('app.name', 'Ali-Kamer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-slate-800 bg-white overflow-hidden">

    <div class="h-screen w-full flex">

        <!-- ================= COLONNE GAUCHE : FORMULAIRE VENDEUR ================= -->
        <div class="w-full lg:w-1/2 h-full flex flex-col justify-between p-6 sm:p-8 lg:p-10 xl:p-12 bg-white overflow-y-auto min-h-0">

            <!-- 1. En-tête / Logo -->
            <div class="flex items-center justify-between shrink-0 mb-3">
                <a href="/" class="group flex items-center gap-3 transition-transform active:scale-95">
                    <div class="p-2 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm group-hover:border-slate-200">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-10 sm:h-11 w-auto object-contain">
                    </div>
                </a>
                <span class="text-xs font-extrabold tracking-wider text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full uppercase">FR</span>
            </div>

            <!-- 2. Section Principale / Formulaire -->
            <div class="my-auto max-w-lg w-full mx-auto py-1">
                
                <!-- Titre & Sous-titre -->
                <div class="mb-4">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Devenir Vendeur Partner</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Ouvrez votre boutique et vendez vos produits dans tout le Cameroun.</p>
                </div>

                <!-- Messages d'Alerte -->
                @if (session('fail'))
                    <div class="mb-3 rounded-2xl border border-danger-200 bg-danger-50/90 p-3 text-xs sm:text-sm text-danger-700 shadow-sm flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0 text-danger" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ session('fail') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-3 rounded-2xl border border-danger-200 bg-danger-50/90 p-3 text-xs text-danger-700 shadow-sm">
                        <p class="font-bold text-xs text-danger-800 mb-0.5">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="list-disc list-inside text-xs text-danger space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Authentification Sociale Vendeur (2 Colonnes) -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <a href="{{ route('social.redirect', ['provider' => 'google', 'role' => 'seller']) }}"
                        class="flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white py-2.5 px-3 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M21.35 12.23c0-.79-.07-1.55-.2-2.28H12v4.31h5.23a4.47 4.47 0 0 1-1.94 2.94v2.45h3.14c1.84-1.69 2.92-4.18 2.92-7.42z" />
                            <path fill="#34A853" d="M12 21.5c2.63 0 4.84-.87 6.45-2.35l-3.14-2.45c-.87.58-1.98.92-3.31.92-2.54 0-4.69-1.72-5.46-4.03H3.3v2.53A9.74 9.74 0 0 0 12 21.5z" />
                            <path fill="#FBBC05" d="M6.54 13.59A5.85 5.85 0 0 1 6.23 12c0-.55.1-1.09.31-1.59V7.88H3.3A9.74 9.74 0 0 0 2.25 12c0 1.57.38 3.05 1.05 4.12l3.24-2.53z" />
                            <path fill="#EA4335" d="M12 6.38c1.43 0 2.71.49 3.72 1.45l2.79-2.79C16.84 3.43 14.63 2.5 12 2.5a9.74 9.74 0 0 0-8.7 5.38l3.24 2.53C7.31 8.1 9.46 6.38 12 6.38z" />
                        </svg>
                        <span> continuer avec Google</span>
                    </a>

                    <a href="{{route('facebook.register', ['role' => 'seller'])}}"
                        class="flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white py-2.5 px-3 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 text-[#1877F2] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.02 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.03 1.79-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.88v2.27h3.34l-.53 3.49h-2.81V24C19.61 23.09 24 18.09 24 12.07z" />
                        </svg>
                        <span> continuer avec Facebook</span>
                    </a>
                </div>

                <!-- Séparateur -->
                <div class="relative flex items-center my-3">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="mx-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 shrink-0">
                        ou avec vos informations
                    </span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Formulaire Vendeur -->
                <form method="POST" action="{{ route('register.seller.post') }}" class="space-y-4">
                    @csrf

                    <!-- SECTION 1 : INFOS PERSONNELLES -->
                    <div class="space-y-3 pt-1">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-600/10 text-[11px] font-extrabold text-primary-600">1</span>
                            <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Informations personnelles</h2>
                        </div>

                        <!-- Nom complet -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Nom complet <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Jean Dupont"
                                class="w-full rounded-2xl border px-3.5 py-2 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                @error('name') border-danger focus:ring-2 focus:ring-danger/10 @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 @enderror focus:outline-none">
                        </div>

                        <!-- Numéro Mobile Money + Choix Opérateur -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Numéro Mobile Money <span class="text-danger">*</span>
                                <span class="text-primary-600 font-semibold normal-case text-[11px]">(pour vos retraits)</span>
                            </label>

                            <!-- Sélection Opérateur -->
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <label class="flex items-center gap-2 border-2 rounded-2xl p-2 cursor-pointer transition-all
                                    {{ old('momo_operator') === 'mtn' ? 'border-warning bg-warning-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="mtn" {{ old('momo_operator') === 'mtn' ? 'checked' : '' }} required class="accent-primary-600">
                                    <span class="text-xs font-bold text-warning-700">MTN MoMo</span>
                                </label>
                                <label class="flex items-center gap-2 border-2 rounded-2xl p-2 cursor-pointer transition-all
                                    {{ old('momo_operator') === 'orange' ? 'border-accent-500 bg-accent-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="orange" {{ old('momo_operator') === 'orange' ? 'checked' : '' }} class="accent-primary-600">
                                    <span class="text-xs font-bold text-accent-600">Orange Money</span>
                                </label>
                            </div>

                            <!-- Champ Téléphone MoMo -->
                            <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50 hover:bg-white focus-within:bg-white
                                @error('phone_momo') border-danger @else border-slate-200 focus-within:border-primary-600 focus-within:ring-4 focus-within:ring-primary-600/10 @enderror">
                                <div class="px-3 flex items-center bg-slate-100 text-slate-600 text-xs font-bold border-r border-slate-200 shrink-0">
                                    🇨🇲 +237
                                </div>
                                <input type="tel" name="phone_momo" value="{{ old('phone_momo') }}" required placeholder="6 XX XX XX XX" maxlength="9"
                                    class="w-full bg-transparent px-3.5 py-2 text-sm focus:outline-none">
                            </div>
                        </div>

                        <!-- Téléphone de contact & Email (Grid 2 cols) -->
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Contact <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                                </label>
                                <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50 hover:bg-white focus-within:bg-white border-slate-200 focus-within:border-primary-600">
                                    <div class="px-2.5 flex items-center bg-slate-100 text-slate-600 text-xs font-bold border-r border-slate-200 shrink-0">
                                        +237
                                    </div>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="6 XX XX XX XX" maxlength="9"
                                        class="w-full bg-transparent px-3 py-2 text-sm focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Adresse e-mail <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="vous@exemple.cm"
                                    class="w-full rounded-2xl border px-3.5 py-2 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                    @error('email') border-danger @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 @enderror focus:outline-none">
                            </div>
                        </div>

                        <!-- Mot de passe + Confirmation (Grid 2 cols) -->
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Mot de passe <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" required placeholder="Min. 8 car."
                                    class="w-full rounded-2xl border px-3.5 py-2 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                    @error('password') border-danger @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 @enderror focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Confirmer <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password_confirmation" required placeholder="Répéter"
                                    class="w-full rounded-2xl border border-slate-200 px-3.5 py-2 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2 : INFOS BOUTIQUE -->
                    <div class="space-y-3 pt-1">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-600/10 text-[11px] font-extrabold text-primary-600">2</span>
                            <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Votre Boutique</h2>
                        </div>

                        <!-- Nom boutique -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Nom de la boutique <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="shop_name" value="{{ old('shop_name') }}" required placeholder="Ex: Tech Shop Douala"
                                class="w-full rounded-2xl border px-3.5 py-2 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white
                                @error('shop_name') border-danger @else border-slate-200 focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 @enderror focus:outline-none">
                        </div>

                        <!-- Ville + Catégorie (Grid 2 cols) -->
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Ville <span class="text-danger">*</span>
                                </label>
                                <select name="city" required
                                    class="w-full rounded-2xl border border-slate-200 px-3 py-2 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 focus:outline-none">
                                    <option value="">-- Choisir --</option>
                                    @foreach (['Douala', 'Yaoundé', 'Bafoussam', 'Bamenda', 'Buea', 'Limbé', 'Garoua', 'Maroua', 'Ngaoundéré', 'Bertoua', 'Ebolowa', 'Kribi'] as $city)
                                        <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Catégorie <span class="text-danger">*</span>
                                </label>
                                <select name="category" required
                                    class="w-full rounded-2xl border border-slate-200 px-3 py-2 text-sm transition bg-slate-50/50 hover:bg-white focus:bg-white focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 focus:outline-none">
                                    <option value="">-- Choisir --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->slug }}" {{ old('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Description <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                            </label>
                            <textarea name="description" rows="2" maxlength="500" placeholder="Décrivez votre boutique..."
                                class="w-full rounded-2xl border border-slate-200 px-3.5 py-2 text-sm bg-slate-50/50 hover:bg-white focus:bg-white focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 focus:outline-none resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Note KYC -->
                    <div class="rounded-2xl border border-warning-200 bg-warning-50/80 p-3 text-xs text-warning-800 shadow-sm">
                        <p class="font-bold mb-0.5 flex items-center gap-1.5 text-warning-800">📋 Étape suivante : Validation KYC</p>
                        <p class="leading-relaxed text-warning-800/90 text-[11px]">
                            Vous soumettrez une pièce d'identité après création. Activation sous 24h à 48h.
                        </p>
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-primary-600 py-3 text-sm font-bold text-white shadow-lg shadow-primary-600/25 transition-all hover:bg-primary-700 active:scale-[0.99] flex items-center justify-center gap-2 group mt-2">
                        <span>Créer mon compte vendeur</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Lien Se Connecter -->
                <p class="mt-4 text-center text-xs sm:text-sm text-slate-500 font-medium">
                    Déjà un compte ?
                    <a href="{{ route('login.show') }}" class="font-bold text-primary-600 hover:underline">Se connecter</a>
                </p>
            </div>

            <!-- 3. Mini Footer / Navigation -->
            <div class="shrink-0 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 mt-2">
                <a href="{{ route('register.show') }}" class="inline-flex items-center gap-1.5 font-bold text-slate-500 hover:text-primary-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Choix des comptes</span>
                </a>
                <span>&copy; {{ date('Y') }} Ali-Kamer</span>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : BANNIÈRE AVEC LOGO INTEGRÉ ================= -->
        <div class="hidden lg:flex w-1/2 h-full bg-primary-800 text-white p-10 xl:p-14 flex-col justify-between relative overflow-hidden shrink-0">

            <!-- Cercles décoratifs d'arrière-plan -->
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-white/5 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-warning/10 blur-3xl pointer-events-none"></div>

            <!-- Logo Ali-Kamer géant en arrière-plan (Filigrane) -->
            <div class="absolute -right-16 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                <img src="{{ asset('images/afrique.png') }}" alt="" class="w-[500px] h-auto object-contain">
            </div>

            <div class="relative z-10">
                <!-- Badge & Grand Logo Ali-Kamer en surbrillance -->
                <div class="flex items-center justify-between">
                    <span class="inline-block text-xs font-bold tracking-widest uppercase text-warning bg-white/10 px-3.5 py-1.5 rounded-full backdrop-blur-md">
                        Espace Vendeurs Partner
                    </span>
                    <div class="p-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 shadow-xl">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-10 w-auto object-contain drop-shadow">
                    </div>
                </div>

                <h2 class="text-3xl xl:text-4xl font-black mt-6 leading-tight tracking-tight">
                    Développez vos ventes <br><span class="text-warning">partout au Cameroun.</span>
                </h2>
                <p class="text-sm text-success-100/90 mt-3 leading-relaxed max-w-md font-normal">
                    Rejoignez la plus grande marketplace locale et touchez des milliers de clients à Douala, Yaoundé et dans toutes les régions.
                </p>
            </div>

            <!-- Avantages Vendeur -->
            <div class="relative z-10 space-y-5 my-auto max-w-md">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        💸
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Paiements directs MoMo & Orange Money</h3>
                        <p class="text-xs text-success-100/80 mt-1 leading-snug">Recevez l'argent de vos ventes directement sur votre compte Mobile Money dès livraison.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        🚚
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Réseau de livraison partenaire</h3>
                        <p class="text-xs text-success-100/80 mt-1 leading-snug">Expédiez facilement vos commandes interurbaines via nos agences partenaires sans tracas.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        📈
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Gestion simple & badge certifié</h3>
                        <p class="text-xs text-success-100/80 mt-1 leading-snug">Tableau de bord intuitif pour gérer vos stocks, commandes et obtenir votre badge vérifié (KYC).</p>
                    </div>
                </div>
            </div>

            <!-- Citation -->
            <div class="relative z-10 pt-5 border-t border-white/10">
                <p class="text-xs sm:text-sm text-success-100/90 italic leading-relaxed">
                    « Ali-Kamer nous permet d'expédier nos produits depuis Douala et Yaoundé en toute sérénité. »
                </p>
                <p class="text-xs font-bold text-warning mt-2">— Vendeur partenaire à Bafoussam</p>
            </div>

        </div>

    </div>

</body>

</html>