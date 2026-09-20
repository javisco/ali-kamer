@extends('base')

@section('title', 'Ali-Kamer — Marketplace Sécurisée au Cameroun & en Afrique')

@section('content')

    {{-- =========================================================
     PAGE D'ACCUEIL ALI-KAMER
     Marketplace e-commerce optimisée :
     - Hero E-Commerce avec proposition de valeur claire (Séquestre Mobile Money & Agences)
     - Barre de réassurance / Confiance e-commerce
     - Navigation rapide par rayons & catégories
     - Grille produits compacte et optimisée (taille réduite sans perte d'information)
     - Workflow d'achat sécurisé (Comment ça marche)
     - Appel à l'action Vendeurs & Réseau d'agences
========================================================= --}}
    <div class="bg-[#FAF9F6] text-slate-800 font-sans">

        {{-- =========================================================
         1. BANDEAU D'ANNONCE EXPRESS (TOP TICKER)
        ========================================================== --}}
        <div class="bg-gradient-to-r from-[#004D2A] via-[#006B32] to-[#004D2A] text-white text-[11px] font-bold py-1.5 px-4 shadow-xs">
            <div class="max-w-[1440px] mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 truncate">
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-[#FCD116] text-[#004D2A] text-[9px] font-black">✓</span>
                    <span class="truncate">Plateforme 100% sécurisée au Cameroun : argent sous séquestre jusqu'à la vérification de votre colis au guichet.</span>
                </div>
                <div class="hidden md:flex items-center gap-4 shrink-0 text-[10px] text-emerald-100">
                    <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-[#FCD116]"></span> MTN MoMo & Orange Money</span>
                    <span>•</span>
                    <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-white"></span> Retrait en agences agréées</span>
                </div>
            </div>
        </div>

        {{-- =========================================================
         2. HERO SECTION — HUB E-COMMERCE & PROMOTIONNEL
        ========================================================== --}}
        <section class="relative overflow-hidden bg-gradient-to-b from-[#F3FBF6] via-white to-[#FAF9F6] border-b border-slate-200/70">
            <div class="relative max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                
                {{-- Grille principale du Hero : 2 colonnes (Bannière Principale + Promos/Vendeur) --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">

                    {{-- Colonne Principale (8 cols sur grand écran) --}}
                    <div class="lg:col-span-8 bg-gradient-to-br from-white via-[#F0FDF4] to-[#FFFBEB] rounded-3xl p-6 sm:p-8 lg:p-10 border border-emerald-100 shadow-sm relative overflow-hidden">
                        
                        {{-- Éléments décoratifs en arrière-plan --}}
                        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-emerald-500/5 pointer-events-none"></div>
                        <div class="absolute right-1/4 top-0 w-48 h-48 rounded-full bg-yellow-500/5 pointer-events-none"></div>

                        <div class="relative z-10 max-w-2xl">
                            
                            {{-- Badge de réassurance --}}
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00843D]/10 text-[#006B32] border border-[#00843D]/20 text-xs font-black tracking-wide mb-3">
                                <span class="flex h-2 w-2 rounded-full bg-[#00843D] animate-pulse"></span>
                                🇨🇲 Marketplace n°1 avec Séquestre Bilatéral
                            </div>

                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-950 tracking-tight leading-[1.15]">
                                Achetez et vendez sans stress,
                                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#00843D] via-[#0A9B4E] to-[#006B32]">
                                    du Cameroun pour l'Afrique.
                                </span>
                            </h1>

                            <p class="mt-3 text-xs sm:text-sm lg:text-[15px] text-slate-600 leading-relaxed max-w-xl">
                                Commandez vos produits auprès de marchands certifiés à <strong>Douala, Yaoundé</strong> et partout au pays. 
                                Votre argent est <span class="text-[#00843D] font-bold">gardé en sécurité</span> et n'est versé au vendeur que lorsque vous validez votre colis avec votre <span class="text-slate-900 font-bold">code secret OTP</span>.
                            </p>

                            {{-- Actions principales --}}
                            <div class="mt-6 flex flex-wrap items-center gap-3">
                                <a href="#produits"
                                   class="inline-flex items-center gap-2 rounded-xl bg-[#00843D] hover:bg-[#006B32] px-6 py-3 text-xs sm:text-sm font-black text-white shadow-md shadow-emerald-900/15 transition hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    Explorer les offres
                                </a>

                                @if (Route::has('register.seller'))
                                    <a href="{{ route('register.seller') }}"
                                       class="inline-flex items-center gap-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 px-5 py-3 text-xs sm:text-sm font-black text-slate-800 transition hover:border-[#00843D] hover:text-[#00843D]">
                                        <svg class="w-4 h-4 text-[#F9A01B]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                        Ouvrir ma boutique
                                    </a>
                                @endif
                            </div>

                            {{-- Micro-garanties sous les boutons --}}
                            <div class="mt-6 pt-5 border-t border-emerald-100/80 flex flex-wrap items-center gap-x-5 gap-y-2 text-[11px] font-bold text-slate-600">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-emerald-100 text-[#00843D] flex items-center justify-center text-[9px] font-black">✓</span>
                                    <span>Paiement MoMo & OM</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-emerald-100 text-[#00843D] flex items-center justify-center text-[9px] font-black">✓</span>
                                    <span>Retrait agences partenaires</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-emerald-100 text-[#00843D] flex items-center justify-center text-[9px] font-black">✓</span>
                                    <span>Garantie 0% arnaque</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Colonne Droite : 2 mini-bannières promotionnelles (4 cols sur grand écran) --}}
                    <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-4">
                        
                        {{-- Mini-bannière 1 : Bonnes Affaires & Ventes Flash --}}
                        <div class="flex-1 bg-gradient-to-br from-amber-500/10 via-white to-red-500/10 border border-amber-200/80 rounded-2xl p-4 sm:p-5 relative overflow-hidden hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#CE1126] text-white text-[10px] font-black uppercase tracking-wider">
                                        ⚡ Ventes Flash
                                    </span>
                                    <h3 class="mt-2 text-sm sm:text-base font-black text-slate-900 leading-snug">
                                        Jusqu'à -40% sur les nouveautés
                                    </h3>
                                    <p class="mt-1 text-[11px] text-slate-500 leading-relaxed">
                                        Offres promotionnelles sur l'électronique, la mode et les produits du terroir.
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-amber-100 text-2xl flex items-center justify-center shrink-0">
                                    🎁
                                </div>
                            </div>
                            <a href="#produits" class="mt-3 inline-flex items-center gap-1.5 text-xs font-black text-[#CE1126] hover:text-red-700 transition">
                                Voir les promotions
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        {{-- Mini-bannière 2 : Espace Vendeur Pro --}}
                        <div class="flex-1 bg-gradient-to-br from-emerald-500/10 via-white to-slate-50 border border-emerald-200/80 rounded-2xl p-4 sm:p-5 relative overflow-hidden hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#00843D] text-white text-[10px] font-black uppercase tracking-wider">
                                        💼 Espace Vendeur
                                    </span>
                                    <h3 class="mt-2 text-sm sm:text-base font-black text-slate-900 leading-snug">
                                        Vendez partout au Cameroun
                                    </h3>
                                    <p class="mt-1 text-[11px] text-slate-500 leading-relaxed">
                                        Touchez des milliers d'acheteurs. Encaissez vos gains directement sur MTN MoMo & Orange Money.
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-2xl flex items-center justify-center shrink-0">
                                    🏪
                                </div>
                            </div>
                            @if (Route::has('register.seller'))
                                <a href="{{ route('register.seller') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-black text-[#00843D] hover:text-[#006B32] transition">
                                    Créer ma boutique gratuitement
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
        </section>

        {{-- =========================================================
         3. BARRE DE RÉASSURANCE E-COMMERCE (4 PILIERS DE CONFIANCE)
        ========================================================== --}}
        <section class="bg-white border-b border-slate-200/60 shadow-2xs">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    
                    {{-- Pilier 1 : Séquestre --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#00843D] flex items-center justify-center text-lg shrink-0 border border-emerald-100">
                            🛡️
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-black text-slate-900 leading-tight truncate">Séquestre Sécurisé</h4>
                            <p class="text-[11px] text-slate-500 leading-tight mt-0.5 truncate">Argent protégé jusqu'au retrait</p>
                        </div>
                    </div>

                    {{-- Pilier 2 : Agences Partenaires --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 border border-blue-100">
                            🚚
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-black text-slate-900 leading-tight truncate">Réseau d'Agences</h4>
                            <p class="text-[11px] text-slate-500 leading-tight mt-0.5 truncate">General Express, Finexs & plus</p>
                        </div>
                    </div>

                    {{-- Pilier 3 : Mobile Money --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0 border border-amber-100">
                            📱
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-black text-slate-900 leading-tight truncate">MoMo & Orange Money</h4>
                            <p class="text-[11px] text-slate-500 leading-tight mt-0.5 truncate">Paiements locaux en FCFA</p>
                        </div>
                    </div>

                    {{-- Pilier 4 : Code OTP --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0 border border-purple-100">
                            🔑
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-black text-slate-900 leading-tight truncate">Code Secret OTP</h4>
                            <p class="text-[11px] text-slate-500 leading-tight mt-0.5 truncate">Preuve irréfutable au guichet</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- =========================================================
         4. RAYONS & CATÉGORIES (EXPLOREZ PAR RAYON)
        ========================================================== --}}
        <section class="bg-[#FAF9F6] py-7 sm:py-9">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <p class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#00843D]">
                            Rayons Populaires
                        </p>
                        <h2 class="text-lg sm:text-xl font-black tracking-tight text-slate-900">
                            Explorez nos catégories
                        </h2>
                    </div>

                    <a href="{{ route('buyer.home') }}"
                       class="inline-flex items-center gap-1 text-xs font-black text-[#00843D] hover:text-[#006B32] transition">
                        <span>Voir tout</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                @php
                    $categoriesList = [
                        ['name' => 'Agriculture', 'slug' => 'agriculture', 'icon' => '🍃', 'tone' => 'green'],
                        ['name' => 'Élevage', 'slug' => 'elevage', 'icon' => '🐄', 'tone' => 'yellow'],
                        ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🧺', 'tone' => 'red'],
                        ['name' => 'Téléphonie', 'slug' => 'telephonie', 'icon' => '📱', 'tone' => 'green'],
                        ['name' => 'Informatique', 'slug' => 'informatique', 'icon' => '💻', 'tone' => 'yellow'],
                        ['name' => 'Maison & Bureau', 'slug' => 'maison', 'icon' => '🏠', 'tone' => 'green'],
                        ['name' => 'Mode & Beauté', 'slug' => 'mode', 'icon' => '👕', 'tone' => 'red'],
                        ['name' => 'Équipements', 'slug' => 'equipements', 'icon' => '⚙️', 'tone' => 'yellow'],
                        ['name' => 'Livres & Formations', 'slug' => 'livres', 'icon' => '📖', 'tone' => 'green'],
                        ['name' => 'Autres', 'slug' => 'autres', 'icon' => '•••', 'tone' => 'red'],
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-5 lg:grid-cols-10 gap-2 sm:gap-2.5">
                    @foreach ($categoriesList as $cat)
                        @php
                            $isActive = request('category') === $cat['slug'];
                            $toneClasses = match ($cat['tone']) {
                                'red' => 'bg-red-50 text-[#CE1126] group-hover:bg-red-100',
                                'yellow' => 'bg-amber-50 text-[#8A7000] group-hover:bg-amber-100',
                                default => 'bg-emerald-50 text-[#00843D] group-hover:bg-emerald-100',
                            };
                        @endphp

                        <a href="{{ route('buyer.home', ['category' => $cat['slug']]) }}"
                           class="group min-w-0 flex flex-col items-center text-center p-2.5 rounded-2xl bg-white border {{ $isActive ? 'border-[#00843D] ring-2 ring-emerald-200 shadow-sm' : 'border-slate-200/80' }} hover:border-[#00843D] hover:-translate-y-0.5 hover:shadow-md transition-all duration-200">

                            <span class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-xl sm:text-2xl {{ $toneClasses }} transition">
                                {{ $cat['icon'] }}
                            </span>

                            <span class="mt-2 text-[10px] sm:text-[11px] font-bold text-slate-700 group-hover:text-[#00843D] leading-tight line-clamp-1 truncate w-full px-1">
                                {{ $cat['name'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- =========================================================
         5. CATALOGUE PRODUITS (GRILLE COMPACTE & FILTRES E-COMMERCE)
        ========================================================== --}}
        <section id="produits" class="bg-white border-y border-slate-200/70 scroll-mt-20 py-8 sm:py-10">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

                {{-- En-tête du catalogue avec titre dynamique et filtres --}}
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                    
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#00843D]"></span>
                            <p class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-[#00843D]">
                                La sélection Ali-Kamer
                            </p>
                        </div>

                        <h2 class="mt-1 text-lg sm:text-2xl font-black tracking-tight text-slate-900">
                            @if (request('q'))
                                Résultats pour <span class="text-[#00843D]">"{{ request('q') }}"</span>
                            @elseif(request('category'))
                                Rayon : <span class="text-[#00843D]">{{ ucfirst(request('category')) }}</span>
                            @else
                                Produits disponibles
                            @endif
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500 font-medium">
                            {{ $products->total() }} {{ $products->total() > 1 ? 'produits prêts pour expédition' : 'produit prêt pour expédition' }}
                        </p>
                    </div>

                    {{-- FILTRES & TRIS E-COMMERCE --}}
                    <div class="flex flex-wrap items-center gap-2">
                        
                        {{-- Filtres Catégories Rapides --}}
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none max-w-full">
                            <a href="{{ route('buyer.home') }}#produits"
                               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-extrabold transition {{ !request('category') ? 'bg-[#00843D] text-white shadow-xs' : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D] hover:text-[#00843D]' }}">
                                Tous
                            </a>

                            <a href="{{ route('buyer.home', array_merge(request()->except('category', 'page'), ['category' => 'agriculture'])) }}#produits"
                               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-extrabold transition {{ request('category') === 'agriculture' ? 'bg-[#00843D] text-white' : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                                🍃 Agriculture
                            </a>

                            <a href="{{ route('buyer.home', array_merge(request()->except('category', 'page'), ['category' => 'elevage'])) }}#produits"
                               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-extrabold transition {{ request('category') === 'elevage' ? 'bg-[#00843D] text-white' : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                                🐄 Élevage
                            </a>

                            <a href="{{ route('buyer.home', array_merge(request()->except('category', 'page'), ['category' => 'informatique'])) }}#produits"
                               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-extrabold transition {{ request('category') === 'informatique' ? 'bg-[#00843D] text-white' : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                                💻 Tech
                            </a>

                            <a href="{{ route('buyer.home', array_merge(request()->except('category', 'page'), ['category' => 'mode'])) }}#produits"
                               class="shrink-0 px-3 py-1.5 rounded-full text-xs font-extrabold transition {{ request('category') === 'mode' ? 'bg-[#00843D] text-white' : 'bg-slate-50 border border-slate-200 text-slate-600 hover:border-[#00843D]' }}">
                                👕 Mode
                            </a>
                        </div>

                        {{-- Sélecteur de Tri E-Commerce --}}
                        <div class="flex items-center gap-1.5 ml-auto">
                            <span class="text-[11px] font-bold text-slate-400 hidden sm:inline">Trier :</span>
                            <form method="GET" action="{{ route('buyer.home') }}" class="inline-block">
                                @if (request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                                @if (request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                @if (request('city')) <input type="hidden" name="city" value="{{ request('city') }}"> @endif
                                @if (request('shipping')) <input type="hidden" name="shipping" value="{{ request('shipping') }}"> @endif

                                <select name="sort" onchange="this.form.submit()"
                                        class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold rounded-lg px-2.5 py-1.5 outline-none focus:border-[#00843D] transition cursor-pointer">
                                    <option value="recent" {{ request('sort', 'recent') === 'recent' ? 'selected' : '' }}>Nouveautés</option>
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Plus populaires</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                </select>
                            </form>
                        </div>

                    </div>
                </div>

                {{-- =========================================================
                 GRILLE PRODUITS — COMPACTE & HAUTE DENSITÉ D'INFORMATION
                 Taille maîtrisée, zéro perte d'information !
                ========================================================== --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2.5 sm:gap-3">

                    @forelse($products as $product)

                        @php
                            $availableStock = $product->availableStock();

                            $hasDiscount = $product->old_price && $product->old_price > $product->price;
                            $discountPercent = 0;

                            if ($hasDiscount && $product->old_price > 0) {
                                $discountPercent = (int) round(
                                    (($product->old_price - $product->price) / $product->old_price) * 100,
                                );
                            }

                            $minQuantity = max(1, (int) ($product->min_quantity ?? 1));

                            $sellerAge = null;

                            if ($product->shop?->user?->created_at) {
                                $createdAt = $product->shop->user->created_at;
                                $now = now();

                                $years = (int) $createdAt->diffInYears($now);
                                $months = (int) $createdAt->diffInMonths($now);
                                $days = (int) $createdAt->diffInDays($now);

                                if ($years >= 1) {
                                    $sellerAge = $years . ' ' . ($years > 1 ? 'ans' : 'an');
                                } elseif ($months >= 1) {
                                    $sellerAge = $months . ' mois';
                                } elseif ($days >= 1) {
                                    $sellerAge = $days . ' ' . ($days > 1 ? 'j' : 'j');
                                } else {
                                    $sellerAge = "récent";
                                }
                            }

                            $salesCount = \App\Models\OrderItem::query()
                                ->where('product_id', $product->id)
                                ->whereHas('order', function ($query) {
                                    $query->whereIn('status', [
                                        \App\Models\Order::STATUS_COMPLETED,
                                        \App\Models\Order::STATUS_AUTO_COMPLETED,
                                    ]);
                                })
                                ->sum('quantity');
                        @endphp

                        {{-- CARTE PRODUIT COMPACTE --}}
                        <a href="{{ route('product.show', $product) }}"
                           class="group min-w-0 bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl overflow-hidden hover:border-[#00843D] hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col">

                            {{-- 1. IMAGE PRODUIT COMPACTE (FORMAT 1:1 CARRÉ) --}}
                            <div class="relative aspect-square bg-slate-50 overflow-hidden">

                                @if ($product->images && $product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                         alt="{{ $product->title }}" loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#FAF9F6] text-slate-300">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- BADGE TRANSPORT COMPACT --}}
                                @if ($product->shipping_included)
                                    <span class="absolute top-1.5 left-1.5 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md bg-[#00843D] text-white text-[8.5px] font-bold shadow-xs">
                                        🚚 Inclus
                                    </span>
                                @else
                                    <span class="absolute top-1.5 left-1.5 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md bg-amber-500 text-slate-950 text-[8.5px] font-bold shadow-xs">
                                        🚚 Non inclus
                                    </span>
                                @endif

                                {{-- BADGE REMISE COMPACT --}}
                                @if ($hasDiscount && $discountPercent > 0)
                                    <span class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-md bg-[#CE1126] text-white text-[9px] font-black shadow-xs">
                                        -{{ $discountPercent }}%
                                    </span>
                                @endif

                                {{-- BADGE STOCK COMPACT --}}
                                @if ($availableStock <= 0 || $product->status === 'sold_out')
                                    <span class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-slate-900/85 text-white text-[8.5px] font-bold backdrop-blur-2xs">
                                        Épuisé
                                    </span>
                                @elseif ($availableStock <= 5)
                                    <span class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-[#FCD116] text-slate-950 text-[8.5px] font-black shadow-xs">
                                        {{ $availableStock }} restant(s)
                                    </span>
                                @endif
                            </div>

                            {{-- 2. INFORMATIONS PRODUIT (ESPACEMENT OPTIMISÉ) --}}
                            <div class="p-2 sm:p-2.5 flex flex-col flex-1 justify-between">

                                <div>
                                    {{-- LIGNE BOUTIQUE & VILLE & ANCIENNETÉ --}}
                                    @if ($product->shop)
                                        <div class="flex items-center justify-between gap-1 text-[10px] text-slate-500 leading-none">
                                            <div class="flex items-center gap-0.5 min-w-0 truncate">
                                                <span class="font-extrabold text-slate-700 truncate group-hover:text-[#00843D] transition">
                                                    {{ $product->shop->name }}
                                                </span>
                                                @if ($product->shop->verified_at)
                                                    <svg class="w-3 h-3 text-[#00843D] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414 0L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l5-5a1 1 0 000-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>

                                            <span class="text-[9px] text-slate-400 shrink-0 truncate">
                                                📍 {{ $product->city ?? ($product->shop->city ?? 'Cameroun') }}
                                            </span>
                                        </div>

                                        @if ($sellerAge)
                                            <div class="text-[9px] text-slate-400 font-medium truncate mt-0.5">
                                                Vendeur depuis {{ $sellerAge }}
                                            </div>
                                        @endif
                                    @endif

                                    {{-- TITRE PRODUIT (2 LIGNES COMPACTES) --}}
                                    <h3 class="mt-1 text-[11.5px] sm:text-[12.5px] font-bold text-slate-900 leading-tight line-clamp-2 group-hover:text-[#00843D] transition min-h-[2.4em]">
                                        {{ $product->title }}
                                    </h3>

                                    {{-- VENTES & COMMANDE MINIMALE --}}
                                    <div class="mt-1 flex items-center justify-between text-[10px] text-slate-500 gap-1">
                                        <span class="truncate">
                                            <strong class="font-bold text-slate-700">{{ number_format($salesCount, 0, ',', ' ') }}</strong>
                                            {{ $salesCount > 1 ? 'ventes' : 'vente' }}
                                        </span>
                                        <span class="shrink-0 inline-flex items-center rounded bg-emerald-50 px-1 py-0.2 text-[8.5px] font-bold text-[#00843D]">
                                            Min. {{ $minQuantity }}
                                        </span>
                                    </div>
                                </div>

                                {{-- PRIX ACTUEL + ANCIEN PRIX BARRÉ + BOUTON D'ACTION RAPIDE --}}
                                <div class="mt-2 pt-1.5 border-t border-slate-100 flex items-center justify-between gap-1">
                                    <div class="min-w-0">
                                        <p class="text-[13px] sm:text-[14.5px] font-black text-[#00843D] leading-none">
                                            @if ($product->hasVariants() && $product->minPrice() !== $product->maxPrice())
                                                <span class="block text-[8.5px] font-bold uppercase tracking-wide text-slate-500">À partir de</span>
                                            @endif
                                            {{ number_format($product->minPrice(), 0, ',', ' ') }}
                                            <span class="text-[8.5px] font-bold text-slate-600">FCFA</span>
                                        </p>

                                        @if ($hasDiscount)
                                            <p class="text-[9.5px] text-[#CE1126] line-through font-semibold leading-tight mt-0.5">
                                                {{ number_format($product->old_price, 0, ',', ' ') }} FCFA
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Bouton action compact --}}
                                    <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-50 text-[#00843D] group-hover:bg-[#00843D] group-hover:text-white flex items-center justify-center transition shrink-0 shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                </div>

                            </div>
                        </a>

                    @empty

                        <div class="col-span-full py-6">
                            <div class="bg-[#FAF9F6] border border-dashed border-slate-300 rounded-3xl p-8 sm:p-10 text-center max-w-md mx-auto">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto text-xl text-slate-400 mb-3 border border-slate-200">
                                    🔍
                                </div>

                                <h3 class="text-sm font-black text-slate-900">
                                    Aucun produit trouvé
                                </h3>

                                <p class="mt-1 text-xs text-slate-500">
                                    Essayez de modifier votre recherche ou de réinitialiser vos filtres.
                                </p>

                                @if (request('q') || request('category') || request('sort'))
                                    <a href="{{ route('buyer.home') }}"
                                       class="inline-flex items-center mt-3 px-4 py-2 rounded-xl bg-[#00843D] text-white text-xs font-bold hover:bg-[#006B32] transition">
                                        Voir tous les produits
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                @if ($products->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>
        </section>

        {{-- =========================================================
         6. PROCESSUS D'ACHAT SÉCURISÉ (COMMENT ÇA MARCHE)
        ========================================================== --}}
        <section class="bg-[#FAF9F6] py-10 sm:py-12">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

                <div class="bg-white border border-slate-200/90 rounded-3xl overflow-hidden shadow-xs">
                    <div class="p-6 sm:p-8 lg:p-10">

                        <div class="max-w-2xl">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-[#00843D] text-[10px] font-black uppercase tracking-wider">
                                Sécurité Maximale
                            </span>
                            <h2 class="mt-2 text-xl sm:text-2xl lg:text-3xl font-black text-slate-900">
                                Comment fonctionne le séquestre Ali-Kamer ?
                            </h2>
                            <p class="mt-1.5 text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Finis les risques d'arnaque en ligne. Vos fonds sont sécurisés du premier clic jusqu'à la remise en main propre.
                            </p>
                        </div>

                        {{-- 4 ÉTAPES CLAIRES --}}
                        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">

                            <div class="bg-slate-50/70 border border-slate-200/60 rounded-2xl p-4 sm:p-5 relative">
                                <span class="w-8 h-8 rounded-xl bg-[#00843D] text-white flex items-center justify-center font-black text-sm mb-3">
                                    1
                                </span>
                                <h3 class="text-sm font-black text-slate-900">Choisissez votre article</h3>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                    Parcourez les produits de marchands certifiés et sélectionnez votre agence de livraison préférée.
                                </p>
                            </div>

                            <div class="bg-slate-50/70 border border-slate-200/60 rounded-2xl p-4 sm:p-5 relative">
                                <span class="w-8 h-8 rounded-xl bg-[#F9A01B] text-slate-950 flex items-center justify-center font-black text-sm mb-3">
                                    2
                                </span>
                                <h3 class="text-sm font-black text-slate-900">Payez sous séquestre</h3>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                    Réglez via MTN MoMo ou Orange Money. Vos fonds restent bloqués chez Ali-Kamer sans toucher le vendeur.
                                </p>
                            </div>

                            <div class="bg-slate-50/70 border border-slate-200/60 rounded-2xl p-4 sm:p-5 relative">
                                <span class="w-8 h-8 rounded-xl bg-[#CE1126] text-white flex items-center justify-center font-black text-sm mb-3">
                                    3
                                </span>
                                <h3 class="text-sm font-black text-slate-900">Expédition en agence</h3>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                    Le vendeur dépose le colis au guichet de l'agence partenaire (General Express, Finexs, etc.) avec bordereau.
                                </p>
                            </div>

                            <div class="bg-slate-50/70 border border-slate-200/60 rounded-2xl p-4 sm:p-5 relative">
                                <span class="w-8 h-8 rounded-xl bg-[#00843D] text-white flex items-center justify-center font-black text-sm mb-3">
                                    4
                                </span>
                                <h3 class="text-sm font-black text-slate-900">Vérification & OTP</h3>
                                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                                    Inspectez votre colis au guichet. Donnez votre code OTP verbal pour valider le retrait et payer le vendeur.
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- BANDEAU CTA BAS --}}
                    <div class="border-t border-slate-100 bg-gradient-to-r from-emerald-50 via-white to-amber-50 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-[#FCD116] flex items-center justify-center text-xl shrink-0">
                                🤝
                            </span>
                            <div>
                                <h3 class="text-xs sm:text-sm font-black text-slate-900">
                                    Ali-Kamer — Le commerce de proximité, la sécurité en plus.
                                </h3>
                                <p class="text-[11px] text-slate-500">
                                    Une plateforme construite au Cameroun pour dynamiser l'économie locale.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('tutorials.index') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold transition">
                                Guide d'utilisation
                            </a>
                            <a href="{{ route('about') }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#00843D] hover:bg-[#006B32] text-white text-xs font-extrabold transition">
                                En savoir plus
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        {{-- =========================================================
         7. CHIFFRES CLÉS & RÉSEAU DE CONFIANCE
        ========================================================== --}}
        <section class="bg-white border-t border-slate-200/70 py-7 sm:py-9">
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-100">

                    <div class="px-3 py-2">
                        <p class="text-2xl sm:text-3xl font-black text-[#00843D]">100%</p>
                        <p class="mt-1 text-xs font-bold text-slate-600">Paiements sous séquestre</p>
                        <p class="text-[10px] text-slate-400">Protection totale acheteur & vendeur</p>
                    </div>

                    <div class="px-3 py-2">
                        <p class="text-2xl sm:text-3xl font-black text-[#CE1126]">10 Régions</p>
                        <p class="mt-1 text-xs font-bold text-slate-600">Couverture nationale</p>
                        <p class="text-[10px] text-slate-400">Expéditions interurbaines quotidiennes</p>
                    </div>

                    <div class="px-3 py-2">
                        <p class="text-2xl sm:text-3xl font-black text-amber-600">+30 Agences</p>
                        <p class="mt-1 text-xs font-bold text-slate-600">Points relais agréés</p>
                        <p class="text-[10px] text-slate-400">General Express, Finexs, Touristique...</p>
                    </div>

                    <div class="px-3 py-2">
                        <p class="text-2xl sm:text-3xl font-black text-[#00843D]">0 FCFA</p>
                        <p class="mt-1 text-xs font-bold text-slate-600">Risque d'arnaque</p>
                        <p class="text-[10px] text-slate-400">Validation physique par code secret OTP</p>
                    </div>

                </div>

            </div>
        </section>

    </div>
@endsection