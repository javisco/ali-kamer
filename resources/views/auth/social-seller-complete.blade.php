<!DOCTYPE html>
<html lang="fr" class="bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer votre boutique — {{ config('app.name', 'Ali-Kamer') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased text-slate-800 bg-white overflow-y-auto">

    <div class="min-h-screen w-full bg-white flex flex-col lg:flex-row">

        <!-- =====================================================
             COLONNE GAUCHE : FORMULAIRE COMPACT & LISIBLE
        ====================================================== -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-8 lg:p-10 xl:p-12 bg-white shrink-0">

            <!-- En-tête / Logo -->
            <div class="flex items-center justify-between shrink-0 mb-6 lg:mb-8">
                <a href="/" class="group flex items-center gap-2 transition-transform active:scale-95">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 shadow-sm group-hover:border-slate-200">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-9 sm:h-10 w-auto object-contain">
                    </div>
                </a>
                <span class="text-xs font-extrabold tracking-wider text-slate-600 bg-slate-100 px-3 py-1.5 rounded-full uppercase">FR</span>
            </div>

            <!-- Section Principale / Formulaire -->
            <div class="my-auto max-w-xl w-full mx-auto py-2">
                
                <!-- Titre & Sous-titre -->
                <div class="mb-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-600/10 text-primary-600 text-xs font-bold mb-2.5">
                        <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                        Inscription vendeur
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Créez votre boutique</h1>
                    <p class="text-sm text-slate-600 mt-1.5 font-medium">
                        Votre identité Google a été récupérée. Complétez maintenant les informations de votre activité.
                    </p>
                </div>

                <!-- COMPTE GOOGLE -->
                <div class="mb-5 rounded-xl bg-slate-50 border border-slate-200 p-3.5 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 min-w-0">
                        @if(!empty($socialPending['avatar']))
                            <img src="{{ $socialPending['avatar'] }}" alt="Profil Google" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-black text-sm shrink-0">
                                {{ strtoupper(substr($socialPending['name'] ?? 'A', 0, 1)) }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">
                                {{ $socialPending['name'] ?? 'Utilisateur Google' }}
                            </p>
                            @if(!empty($socialPending['provider_email']))
                                <p class="text-xs text-slate-500 truncate">
                                    {{ $socialPending['provider_email'] }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-success-100 text-success-800 text-xs font-extrabold shrink-0 border border-success-200">
                        ✓ Google
                    </span>
                </div>

                <!-- ERREURS -->
                @if($errors->any())
                    <div class="mb-5 rounded-xl border border-danger-200 bg-danger-50 p-3.5 text-sm text-danger-700 shadow-sm">
                        <p class="font-bold text-xs text-danger-800 mb-1">Vérifiez les informations suivantes :</p>
                        <ul class="list-disc list-inside text-xs text-danger space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('social.complete.seller') }}" class="space-y-5">
                    @csrf

                    <!-- PAIEMENTS MOBILE MONEY -->
                    <div class="space-y-3.5">
                        <div class="flex items-center gap-2 border-b border-slate-200 pb-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-600/10 text-xs font-extrabold text-primary-600">1</span>
                            <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Paiements Mobile Money</h2>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                                    Numéro Mobile Money <span class="text-danger">*</span>
                                </label>
                                <span class="text-primary-600 font-semibold text-xs">(retraits)</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5 mb-2.5">
                                <label class="flex items-center gap-2 border rounded-xl p-3 cursor-pointer transition-all
                                    {{ old('momo_operator') === 'mtn' ? 'border-warning bg-warning-50/60 ring-2 ring-warning/20' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="mtn" {{ old('momo_operator') === 'mtn' ? 'checked' : '' }} required class="accent-primary-600 w-4 h-4">
                                    <span class="text-xs font-bold text-warning-800">MTN MoMo</span>
                                </label>

                                <label class="flex items-center gap-2 border rounded-xl p-3 cursor-pointer transition-all
                                    {{ old('momo_operator') === 'orange' ? 'border-accent-500 bg-accent-50/60 ring-2 ring-accent-500/20' : 'border-slate-200 hover:border-slate-300' }}">
                                    <input type="radio" name="momo_operator" value="orange" {{ old('momo_operator') === 'orange' ? 'checked' : '' }} class="accent-primary-600 w-4 h-4">
                                    <span class="text-xs font-bold text-accent-700">Orange Money</span>
                                </label>
                            </div>

                            <div class="flex rounded-xl border overflow-hidden transition bg-slate-50 hover:bg-white focus-within:bg-white
                                @error('phone_momo') border-danger @else border-slate-200 focus-within:border-primary-600 focus-within:ring-2 focus-within:ring-primary-600/20 @enderror">
                                <div class="px-3.5 flex items-center bg-slate-100 text-slate-700 text-xs font-bold border-r border-slate-200 shrink-0">
                                    🇨🇲 +237
                                </div>
                                <input type="tel" name="phone_momo" value="{{ old('phone_momo') }}" required placeholder="6 XX XX XX XX" maxlength="9"
                                    class="w-full bg-transparent px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none font-medium">
                            </div>
                        </div>

                        <!-- TÉLÉPHONE DIRECT -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                                Téléphone d'appel <span class="text-slate-400 font-normal">(optionnel)</span>
                            </label>
                            <div class="flex rounded-xl border overflow-hidden transition bg-slate-50 hover:bg-white focus-within:bg-white border-slate-200 focus-within:border-primary-600 focus-within:ring-2 focus-within:ring-primary-600/20">
                                <div class="px-3.5 flex items-center bg-slate-100 text-slate-700 text-xs font-bold border-r border-slate-200 shrink-0">
                                    +237
                                </div>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="6 XX XX XX XX" maxlength="9"
                                    class="w-full bg-transparent px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- INFORMATION BOUTIQUE -->
                    <div class="space-y-3.5 pt-2">
                        <div class="flex items-center gap-2 border-b border-slate-200 pb-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-600/10 text-xs font-extrabold text-primary-600">2</span>
                            <h2 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Votre Boutique</h2>
                        </div>

                        <!-- Nom de la boutique -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                                Nom de la boutique <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="shop_name" value="{{ old('shop_name') }}" required placeholder="Ex : Ma Boutique"
                                class="w-full rounded-xl border px-3.5 py-2.5 text-sm text-slate-900 transition bg-slate-50 hover:bg-white focus:bg-white
                                @error('shop_name') border-danger @else border-slate-200 focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 @enderror focus:outline-none font-medium">
                        </div>

                        <!-- Ville + Catégorie -->
                        <div class="grid sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                                    Ville <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="city" value="{{ old('city') }}" required placeholder="Ex : Yaoundé"
                                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 transition bg-slate-50 hover:bg-white focus:bg-white focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 focus:outline-none font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                                    Catégorie <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="category" value="{{ old('category') }}" required placeholder="Ex : Électronique"
                                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 transition bg-slate-50 hover:bg-white focus:bg-white focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 focus:outline-none font-medium">
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                                Description <span class="text-slate-400 font-normal">(optionnelle)</span>
                            </label>
                            <textarea name="description" rows="3" placeholder="Présentez brièvement votre boutique..."
                                class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 bg-slate-50 hover:bg-white focus:bg-white focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 focus:outline-none font-medium resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Bouton Submit -->
                    <button type="submit"
                        class="w-full rounded-xl bg-primary-600 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary-600/25 transition-all hover:bg-primary-800 active:scale-[0.99] flex items-center justify-center gap-2 group mt-2">
                        <span>Créer ma boutique</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <p class="text-center text-xs text-slate-500 mt-2 font-medium">
                        Après la création de votre boutique, vous serez redirigé vers l'étape suivante.
                    </p>
                </form>

            </div>

            <!-- Mini Footer -->
            <div class="shrink-0 pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>&copy; {{ date('Y') }} Ali-Kamer</span>
                <span>Vendre au Cameroun</span>
            </div>
        </div>


        <!-- =====================================================
             PANNEAU DROIT : MARKETING & PRESENTATION
        ====================================================== -->
        <div class="hidden lg:flex w-1/2 min-h-full bg-gradient-to-br from-primary-800 via-primary-600 to-[#00381d] text-white p-8 xl:p-12 flex-col justify-between relative overflow-hidden shrink-0">

            <!-- Cercles décoratifs d'arrière-plan -->
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-warning/15 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-[#CE1126]/15 blur-3xl pointer-events-none"></div>

            <!-- Filigrane Logo Afrique -->
            <div class="absolute -right-12 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                <img src="{{ asset('images/afrique.png') }}" alt="" class="w-[500px] h-auto object-contain">
            </div>

            <div class="relative z-10">
                <!-- En-tête Droite : SUBTIMÉ AVEC GLASSMORPHISM & HALO -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-2 text-xs font-black tracking-widest uppercase text-success-100 bg-white/10 border border-white/20 px-3.5 py-1.5 rounded-full backdrop-blur-md shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span>
                        Marketplace Cameroun
                    </span>

                    <!-- CARTE DU LOGO SUBTIMÉE -->
                    <div class="relative group">
                        <!-- Halo doré en arrière plan -->
                        <div class="absolute -inset-1 bg-gradient-to-r from-warning/30 to-[#CE1126]/20 rounded-2xl blur-md opacity-70 group-hover:opacity-100 transition duration-500"></div>

                        <!-- Card Principale -->
                        <div class="relative px-3.5 py-2 rounded-2xl bg-slate-900/40 backdrop-blur-xl border border-white/20 shadow-2xl flex items-center gap-3">
                            <div class="p-1.5 rounded-xl bg-white/15 border border-white/20 shadow-inner">
                                <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer Logo" class="h-9 w-auto object-contain drop-shadow-[0_4px_8px_rgba(0,0,0,0.3)]">
                            </div>
                            <div class="flex flex-col pr-1">
                                <span class="font-black text-base text-white tracking-wider leading-none">
                                    ALI<span class="text-warning">-KAMER</span>
                                </span>
                                <span class="text-[9px] font-bold text-success-200/80 tracking-widest uppercase mt-0.5">Plateforme Vendeurs</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl xl:text-3xl font-black mt-10 leading-tight tracking-tight">
                    Vendez au Cameroun. <br>
                    <span class="text-warning">Développez votre activité.</span>
                </h2>

                <p class="text-sm text-success-100/90 mt-3 leading-relaxed max-w-md font-medium">
                    Créez votre boutique et présentez vos produits aux acheteurs partout au Cameroun.
                </p>
            </div>

            <!-- ÉTAPES ET AVANTAGES -->
            <div class="relative z-10 space-y-4 my-8 max-w-md">

                <!-- Étape 1 -->
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-warning text-slate-900 flex items-center justify-center font-black text-base shrink-0 shadow-md">
                        1
                    </div>
                    <div>
                        <p class="text-base font-black text-white">Créez votre boutique</p>
                        <p class="text-xs text-white/80 mt-0.5 font-medium">Présentez votre activité.</p>
                    </div>
                </div>

                <!-- Étape 2 -->
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-[#CE1126] text-white flex items-center justify-center font-black text-base shrink-0 shadow-md">
                        2
                    </div>
                    <div>
                        <p class="text-base font-black text-white">Ajoutez vos produits</p>
                        <p class="text-xs text-white/80 mt-0.5 font-medium">Commencez à vendre sur Ali-Kamer.</p>
                    </div>
                </div>

                <!-- Étape 3 -->
                <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-warning text-slate-900 flex items-center justify-center font-black text-base shrink-0 shadow-md">
                        3
                    </div>
                    <div>
                        <p class="text-base font-black text-white">Développez vos ventes</p>
                        <p class="text-xs text-white/80 mt-0.5 font-medium">Touchez davantage de clients.</p>
                    </div>
                </div>

                <!-- Message Bas -->
                <div class="rounded-2xl bg-white/10 border border-white/15 p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-warning text-slate-900 flex items-center justify-center font-black shrink-0 text-base shadow">
                            ✓
                        </div>
                        <div>
                            <p class="text-sm font-black text-white">Pensé pour les vendeurs camerounais</p>
                            <p class="text-xs text-white/80 mt-0.5 font-medium">Achetez et vendez sans stress.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Couleurs du Drapeau -->
            <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-12 rounded-full bg-primary-600"></span>
                    <span class="h-2 w-12 rounded-full bg-warning"></span>
                    <span class="h-2 w-12 rounded-full bg-[#CE1126]"></span>
                </div>
                <span class="text-xs text-white/60 font-semibold">Ali-Kamer Marketplace</span>
            </div>

        </div>

    </div>

</body>

</html>