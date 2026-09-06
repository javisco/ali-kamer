@php
    $layout = auth()->user()->isBuyer()
        ? 'layouts.buyer'
        : 'layouts.seller';
@endphp

@extends($layout)
@section('title', 'À propos — Ali-Kamer')
@section('meta_description', 'Découvrez la mission et le fonctionnement d’Ali-Kamer, marketplace camerounaise.')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Hero Header --}}
        <section class="rounded-3xl bg-slate-950 px-6 py-10 text-white sm:px-10 lg:px-12 relative overflow-hidden border-b-4 border-[#FCD116]">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-[#00843D]/20 rounded-full blur-3xl pointer-events-none"></div>
            <span class="inline-block rounded-full bg-[#00843D]/20 px-3 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-[#FCD116]">À propos d'Ali-Kamer</span>
            <h1 class="mt-4 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl lg:text-5xl leading-tight">
                Une marketplace pensée pour acheter et vendre au <span class="text-[#00843D]">Cameroun</span> <span class="text-[#CE1126]">avec</span> <span class="text-[#FCD116]">confiance.</span>
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-300">
                Ali-Kamer met en relation acheteurs et vendeurs tout en structurant la transaction, le paiement et l'acheminement des commandes.
            </p>
        </section>

        {{-- 3 Piliers --}}
        <section class="mt-8 grid gap-4 md:grid-cols-3">
            <article class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl text-[#00843D]">🛒</div>
                <h2 class="mt-4 text-base font-black text-slate-900">Acheter simplement</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Rechercher, découvrir les produits et accéder rapidement aux informations utiles avant de commander.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl text-[#00843D]">🔐</div>
                <h2 class="mt-4 text-base font-black text-slate-900">Protéger la transaction</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Le système de paiement séquestre est au cœur du parcours : le paiement et la livraison sont traités comme deux étapes liées.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl text-[#00843D]">🚚</div>
                <h2 class="mt-4 text-base font-black text-slate-900">Faciliter l'acheminement</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    La plateforme est conçue pour prendre en compte les commandes pouvant circuler entre différentes villes et passer par des agences.
                </p>
            </article>
        </section>

        {{-- Vision & Principes --}}
        <section class="mt-8 grid gap-8 lg:grid-cols-[1.1fr_.9fr]">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.18em] text-[#CE1126]">Notre vision</span>
                <h2 class="mt-2 text-2xl font-black text-slate-900">Rendre le commerce en ligne plus rassurant.</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                    <p>
                        Dans une marketplace classique, l'acheteur doit souvent faire confiance au vendeur avant de recevoir son produit. Ali-Kamer cherche à réduire cette difficulté en structurant le parcours de commande autour de la protection de la transaction.
                    </p>
                    <p>
                        Le système prévoit également des états de commande permettant de suivre l'évolution d'une commande, depuis le paiement jusqu'à l'acheminement, la réception et la validation.
                    </p>
                    <p>
                        Cette approche permet de créer un environnement dans lequel acheteurs, vendeurs et acteurs logistiques peuvent intervenir avec des responsabilités mieux définies.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-6">
                <h2 class="text-base font-black text-slate-900">Les principes d'Ali-Kamer</h2>
                <div class="mt-4 space-y-3">
                    <div class="flex gap-3 rounded-xl bg-white p-3.5 shadow-sm border border-slate-100">
                        <span class="text-lg">🛡️</span>
                        <div>
                            <p class="text-xs font-black text-slate-900">Protection acheteur</p>
                            <p class="mt-0.5 text-[11px] text-slate-500">La réception et la validation font partie du parcours.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-xl bg-white p-3.5 shadow-sm border border-slate-100">
                        <span class="text-lg">💳</span>
                        <div>
                            <p class="text-xs font-black text-slate-900">Paiement structuré</p>
                            <p class="mt-0.5 text-[11px] text-slate-500">Les paiements sont intégrés au cycle de commande.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-xl bg-white p-3.5 shadow-sm border border-slate-100">
                        <span class="text-lg">📍</span>
                        <div>
                            <p class="text-xs font-black text-slate-900">Commerce local</p>
                            <p class="mt-0.5 text-[11px] text-slate-500">Une expérience adaptée au contexte camerounais.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-xl bg-white p-3.5 shadow-sm border border-slate-100">
                        <span class="text-lg">🤝</span>
                        <div>
                            <p class="text-xs font-black text-slate-900">Confiance</p>
                            <p class="mt-0.5 text-[11px] text-slate-500">Un cadre commun pour acheteurs et vendeurs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section d'Appel à l'action --}}
        <section class="mt-8 rounded-2xl border border-emerald-100 bg-gradient-to-br from-[#F3FBF6] to-[#FFFDF3] p-6 sm:p-8">
            <h2 class="text-xl font-black text-slate-900">Commencer sur Ali-Kamer</h2>
            <p class="mt-2 max-w-2xl text-xs sm:text-sm leading-relaxed text-slate-600">
                Découvrez les produits depuis l'accueil. Si vous souhaitez passer une commande et accéder à votre espace personnel, connectez-vous ou créez un compte.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('buyer.home') }}"
                    class="rounded-full bg-[#00843D] hover:bg-[#006B32] px-5 py-2.5 text-xs font-black text-white shadow-sm transition">
                    Voir les produits
                </a>
                @guest
                    <a href="{{ route('register.show') }}"
                        class="rounded-full border border-slate-300 bg-white hover:bg-slate-50 px-5 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition">
                        Créer un compte
                    </a>
                @endguest
            </div>
        </section>
    </div>
@endsection