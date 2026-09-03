<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte vendeur — {{ config('app.name', 'Ali-Kamer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-white">

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- ================= COLONNE GAUCHE : FORMULAIRE VENDEUR ================= -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-10 lg:px-12 xl:px-16 overflow-y-auto">
            
            <!-- En-tête / Logo mis en valeur -->
            <div class="flex items-center justify-between mb-6">
                <a href="/" class="group inline-flex items-center gap-3 p-2.5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-center p-1.5 rounded-xl bg-slate-50">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-12 sm:h-14 w-auto object-contain transition-transform group-hover:scale-105">
                    </div>
                </a>
                <span class="text-xs font-bold tracking-wider text-slate-400 bg-slate-100 px-3 py-1.5 rounded-full">FR</span>
            </div>

            <!-- Messages d'erreur globaux -->
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
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Devenir Vendeur Partner</h1>
                    <p class="text-sm text-slate-500 mt-1">Ouvrez votre boutique et vendez vos produits dans tout le Cameroun.</p>
                </div>

                <form method="POST" action="{{ route('register.seller.post') }}" class="space-y-6">
                    @csrf

                    <!-- SECTION 1 : INFOS PERSONNELLES -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#006837]/10 text-xs font-bold text-[#006837]">1</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Informations personnelles</h2>
                        </div>

                        <!-- Nom complet -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Jean Dupont"
                                class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50
                                @error('name') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                            @error('name')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <!-- Numéro Mobile Money + Choix Opérateur -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Numéro Mobile Money <span class="text-red-500">*</span>
                                <span class="text-[#006837] font-normal normal-case">(pour recevoir vos paiements)</span>
                            </label>

                            <!-- Sélection Opérateur -->
                            <div class="grid grid-cols-2 gap-3 mb-2">
                                <label class="flex items-center gap-2.5 border-2 rounded-2xl p-3 cursor-pointer transition-all
                                              {{ old('momo_operator') === 'mtn' ? 'border-yellow-400 bg-yellow-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="mtn" {{ old('momo_operator') === 'mtn' ? 'checked' : '' }} required class="accent-[#006837]">
                                    <span class="text-xs font-bold text-yellow-700">MTN MoMo</span>
                                </label>
                                <label class="flex items-center gap-2.5 border-2 rounded-2xl p-3 cursor-pointer transition-all
                                              {{ old('momo_operator') === 'orange' ? 'border-orange-400 bg-orange-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="orange" {{ old('momo_operator') === 'orange' ? 'checked' : '' }} class="accent-[#006837]">
                                    <span class="text-xs font-bold text-orange-600">Orange Money</span>
                                </label>
                            </div>
                            @error('momo_operator')<p class="text-xs text-red-600 mb-2 font-medium">{{ $message }}</p>@enderror

                            <!-- Champ Téléphone MoMo -->
                            <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50
                                @error('phone_momo') border-red-500 @else border-slate-200 focus-within:border-[#006837] focus-within:ring-4 focus-within:ring-[#006837]/10 @enderror">
                                <div class="px-3.5 flex items-center bg-slate-100 text-slate-600 text-xs font-semibold border-r border-slate-200 shrink-0">
                                    🇨🇲 +237
                                </div>
                                <input type="tel" name="phone_momo" value="{{ old('phone_momo') }}" required placeholder="6 XX XX XX XX" maxlength="9"
                                    class="w-full bg-transparent px-4 py-3 text-sm focus:outline-none">
                            </div>
                            @error('phone_momo')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            <p class="mt-1 text-[11px] text-slate-400">Le nom enregistré sur cette puce doit correspondre à votre CNI.</p>
                        </div>

                        <!-- Téléphone de contact (Optionnel) -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Téléphone de contact <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                            </label>
                            <div class="flex rounded-2xl border overflow-hidden transition bg-slate-50/50
                                @error('phone') border-red-500 @else border-slate-200 focus-within:border-[#006837] focus-within:ring-4 focus-within:ring-[#006837]/10 @enderror">
                                <div class="px-3.5 flex items-center bg-slate-100 text-slate-600 text-xs font-semibold border-r border-slate-200 shrink-0">
                                    🇨🇲 +237
                                </div>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="6 XX XX XX XX" maxlength="9"
                                    class="w-full bg-transparent px-4 py-3 text-sm focus:outline-none">
                            </div>
                            @error('phone')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Adresse e-mail <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="vous@exemple.cm"
                                class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50
                                @error('email') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                            @error('email')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <!-- Mot de passe + Confirmation -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" required placeholder="Min. 8 caractères"
                                    class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50
                                    @error('password') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                                @error('password')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Confirmer <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" required placeholder="Répéter"
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm transition bg-slate-50/50 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2 : INFOS BOUTIQUE -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#006837]/10 text-xs font-bold text-[#006837]">2</span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Votre Boutique</h2>
                        </div>

                        <!-- Nom boutique -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Nom de la boutique <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="shop_name" value="{{ old('shop_name') }}" required placeholder="Ex: Tech Shop Douala"
                                class="w-full rounded-2xl border px-4 py-3 text-sm transition bg-slate-50/50
                                @error('shop_name') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 @enderror focus:outline-none">
                            @error('shop_name')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>

                        <!-- Ville + Catégorie -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Ville <span class="text-red-500">*</span>
                                </label>
                                <select name="city" required
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm transition bg-slate-50/50 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none
                                    @error('city') border-red-500 @enderror">
                                    <option value="">-- Choisir --</option>
                                    @foreach(['Douala','Yaoundé','Bafoussam','Bamenda','Buea','Limbé','Garoua','Maroua','Ngaoundéré','Bertoua','Ebolowa','Kribi'] as $city)
                                        <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                    @endforeach
                                </select>
                                @error('city')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select name="category" required
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm transition bg-slate-50/50 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none
                                    @error('category') border-red-500 @enderror">
                                    <option value="">-- Choisir --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->slug }}" {{ old('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Description <span class="text-slate-400 font-normal normal-case">(optionnel)</span>
                            </label>
                            <textarea name="description" rows="3" maxlength="500" placeholder="Décrivez votre boutique en quelques mots..."
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm bg-slate-50/50 focus:border-[#006837] focus:ring-4 focus:ring-[#006837]/10 focus:outline-none resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Note KYC -->
                    <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-4 text-xs text-amber-900 shadow-sm">
                        <p class="font-bold mb-1 flex items-center gap-1.5 text-amber-800">📋 Prochaine étape après l'inscription</p>
                        <p class="leading-relaxed text-amber-800/90">
                            Vous devrez soumettre un dossier KYC (CNI + selfie + numéro MoMo). Votre boutique sera activée après validation par notre équipe sous 24 à 48h.
                        </p>
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-2xl bg-[#006837] py-3.5 text-sm font-bold text-white shadow-lg shadow-[#006837]/20 transition hover:bg-[#004d28] active:scale-[0.99] flex items-center justify-center gap-2">
                        <span>Créer mon compte vendeur</span>
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

            <!-- Mini Footer / Retour aux choix -->
            <div class="text-[11px] text-slate-400 flex items-center justify-between pt-4 border-t border-slate-100 mt-4">
                <a href="{{ route('register.show') }}" class="inline-flex items-center gap-1.5 font-semibold text-slate-500 hover:text-[#006837] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Retour au choix des comptes</span>
                </a>
                <span>&copy; {{ date('Y') }} Ali-Kamer</span>
            </div>
        </div>

        <!-- ================= COLONNE DROITE : BANNIÈRE VENDEUR ================= -->
        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-[#004d28] via-[#006837] to-[#046A38] text-white p-12 xl:p-16 flex-col justify-between relative overflow-hidden">
            
            <!-- Cercles décoratifs d'arrière-plan -->
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-28 -left-24 w-96 h-96 rounded-full bg-[#FFC20E]/20 blur-3xl"></div>

            <div class="relative z-10">
                <span class="text-xs font-bold tracking-widest uppercase text-[#FFC20E] bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                    Espace Vendeurs Partner
                </span>
                
                <h2 class="text-4xl xl:text-5xl font-black mt-6 leading-tight">
                    Développez vos ventes <br><span class="text-[#FFC20E]">partout au Cameroun.</span>
                </h2>
                <p class="text-sm text-emerald-100/90 mt-4 leading-relaxed max-w-lg">
                    Rejoignez la plus grande marketplace locale et touchez des milliers de clients à Douala, Yaoundé et dans toutes les régions.
                </p>
            </div>

            <!-- Avantages Vendeur -->
            <div class="relative z-10 space-y-6 my-auto max-w-lg">
                
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        💸
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Paiements directs MoMo & Orange Money</h3>
                        <p class="text-xs text-emerald-100/80 mt-0.5">Recevez l'argent de vos ventes directement sur votre compte Mobile Money dès livraison.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        🚚
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Réseau de livraison partenaire</h3>
                        <p class="text-xs text-emerald-100/80 mt-0.5">Expédiez facilement vos commandes interurbaines via nos agences partenaires sans tracas.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center shrink-0 text-lg">
                        📈
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white">Gestion simple & badge certifié</h3>
                        <p class="text-xs text-emerald-100/80 mt-0.5">Tableau de bord intuitif pour gérer vos stocks, commandes et obtenir votre badge vérifié (KYC).</p>
                    </div>
                </div>

            </div>

            <!-- Citation -->
            <div class="relative z-10 pt-6 border-t border-white/10">
                <p class="text-xs text-emerald-100 italic leading-relaxed">
                    « Ali-Kamer nous permet d'expédier nos produits depuis Douala et Yaoundé en toute sérénité. »
                </p>
                <p class="text-xs font-bold text-[#FFC20E] mt-2">— Vendeur partenaire à Bafoussam</p>
            </div>

        </div>

    </div>

</body>
</html>