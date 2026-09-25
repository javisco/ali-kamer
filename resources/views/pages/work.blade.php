@extends('base')
 
@section('title', 'Comment ça marche — Ali-Kamer')
 
@section('content')
 
    <div class="bg-[#FAF9F6] text-slate-800">
 
        {{-- =========================================================
        6. COMMENT ÇA MARCHE
    ========================================================== --}}
        <section id="fonctionnement" class="bg-[#FAF9F6] py-10 sm:py-14 scroll-mt-20">
 
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
 
                <div class="text-center max-w-2xl mx-auto">
 
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-[#00843D] text-[10px] font-black uppercase tracking-wider">
                        Comment ça marche ?
                    </span>
 
                    <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                        Acheter devient simple.
                    </h1>
 
                    <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Ali-Kamer organise les principales étapes entre l'achat,
                        le paiement, l'expédition et la réception.
                    </p>
 
                </div>
 
 
                <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
 
                    {{-- Étape 1 --}}
                    <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
 
                        <div class="flex items-center justify-between">
 
                            <span
                                class="w-9 h-9 rounded-xl bg-emerald-50 text-[#00843D] flex items-center justify-center text-sm font-black">
                                01
                            </span>
 
                            <span class="text-xl">
                                🛒
                            </span>
 
                        </div>
 
                        <h3 class="mt-4 text-sm font-black text-slate-900">
                            Choisissez
                        </h3>
 
                        <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                            Trouvez un produit, consultez ses informations et passez votre commande.
                        </p>
 
                    </div>
 
 
                    {{-- Étape 2 --}}
                    <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
 
                        <div class="flex items-center justify-between">
 
                            <span
                                class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-black">
                                02
                            </span>
 
                            <span class="text-xl">
                                📱
                            </span>
 
                        </div>
 
                        <h3 class="mt-4 text-sm font-black text-slate-900">
                            Payez
                        </h3>
 
                        <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                            Réglez votre commande avec les moyens de paiement Mobile Money disponibles.
                        </p>
 
                    </div>
 
 
                    {{-- Étape 3 --}}
                    <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
 
                        <div class="flex items-center justify-between">
 
                            <span
                                class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-black">
                                03
                            </span>
 
                            <span class="text-xl">
                                🚚
                            </span>
 
                        </div>
 
                        <h3 class="mt-4 text-sm font-black text-slate-900">
                            Expédition
                        </h3>
 
                        <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                            Le vendeur prépare et remet le colis au point d'expédition prévu.
                        </p>
 
                    </div>
 
 
                    {{-- Étape 4 --}}
                    <div class="relative bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
 
                        <div class="flex items-center justify-between">
 
                            <span
                                class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-black">
                                04
                            </span>
 
                            <span class="text-xl">
                                🔑
                            </span>
 
                        </div>
 
                        <h3 class="mt-4 text-sm font-black text-slate-900">
                            Réception
                        </h3>
 
                        <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">
                            Vérifiez votre colis au retrait et utilisez le processus de confirmation prévu.
                        </p>
 
                    </div>
 
                </div>
 
 
                {{-- CTA --}}
                <div
                    class="mt-6 rounded-2xl bg-gradient-to-r from-[#004D2A] to-[#00843D] p-5 sm:p-6 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
 
                    <div>
 
                        <p class="text-sm font-black">
                            Besoin de comprendre chaque étape ?
                        </p>
 
                        <p class="mt-1 text-[11px] text-emerald-100">
                            Consultez les guides Ali-Kamer avant votre première commande.
                        </p>
 
                    </div>
 
                    <div class="flex flex-wrap gap-2">
 
                        <a href="{{ route('buyer.home') }}#produits"
                            class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-[#FCD116] text-[#004D2A] text-xs font-black hover:bg-white transition">
                            Voir les produits
                        </a>
 
                        @if (Route::has('tutorials.index'))
                            <a href="{{ route('tutorials.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white text-[#004D2A] text-xs font-black hover:bg-[#FCD116] transition">
                                Guide d'utilisation
                            </a>
                        @endif
 
                        @if (Route::has('about'))
                            <a href="{{ route('about') }}"
                                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-xs font-black hover:bg-white/15 transition">
                                En savoir plus
                            </a>
                        @endif
 
                    </div>
 
                </div>
 
            </div>
        </section>
 
 
        {{-- =========================================================
        8. IDENTITÉ ALI-KAMER
    ========================================================== --}}
        <section class="bg-[#004D2A] text-white py-9 sm:py-11">
 
            <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
 
                <div class="grid md:grid-cols-3 gap-7 md:gap-10 items-center">
 
                    <div class="md:col-span-2">
 
                        <div class="flex items-center gap-3">
 
                            <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center">
                                <span class="text-xl">
                                    🇨🇲
                                </span>
                            </div>
 
                            <div>
 
                                <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-200 font-black">
                                    Notre ambition
                                </p>
 
                                <h2 class="text-xl sm:text-2xl font-black">
                                    Construit au Cameroun pour l'Afrique.
                                </h2>
 
                            </div>
 
                        </div>
 
                        <p class="mt-4 text-xs sm:text-sm text-emerald-100/80 leading-relaxed max-w-2xl">
                            Ali-Kamer veut faciliter le commerce entre acheteurs et vendeurs
                            tout en construisant progressivement un environnement de confiance,
                            adapté aux réalités du marché camerounais.
                        </p>
 
                    </div>
 
 
                    <div class="grid grid-cols-2 gap-3">
 
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xl font-black text-[#FCD116]">
                                🇨🇲
                            </p>
                            <p class="mt-1 text-[10px] font-bold text-emerald-100">
                                Pensé pour le Cameroun
                            </p>
                        </div>
 
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xl font-black text-[#FCD116]">
                                🌍
                            </p>
                            <p class="mt-1 text-[10px] font-bold text-emerald-100">
                                Vision africaine
                            </p>
                        </div>
 
                    </div>
 
                </div>
 
            </div>
 
        </section>
 
    </div>
 
@endsection
 