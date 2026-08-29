{{-- resources/views/pages/about.blade.php --}}
@extends('base')

@section('title', 'À propos — Ali-Kamer')
@section('meta_description', 'Découvrez la mission et le fonctionnement d’Ali-Kamer, marketplace camerounaise.')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-3xl bg-slate-950 px-6 py-8 text-white sm:px-10 lg:px-12">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-400">À propos d'Ali-Kamer</span>
            <h1 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">
                Une marketplace pensée pour acheter et vendre au Cameroun avec davantage de confiance.
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-300">
                Ali-Kamer met en relation acheteurs et vendeurs tout en structurant la transaction, le paiement et
                l'acheminement des commandes.
            </p>
        </section>

        <section class="mt-8 grid gap-4 md:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-lg">🛒</div>
                <h2 class="mt-4 text-base font-black">Acheter simplement</h2>
                <p class="mt-2 text-xs leading-5 text-slate-500">
                    Rechercher, découvrir les produits et accéder rapidement aux informations utiles avant de commander.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-lg">🔐</div>
                <h2 class="mt-4 text-base font-black">Protéger la transaction</h2>
                <p class="mt-2 text-xs leading-5 text-slate-500">
                    Le système de paiement séquestre est au cœur du parcours : le paiement et la livraison sont traités
                    comme deux étapes liées.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-lg">🚚</div>
                <h2 class="mt-4 text-base font-black">Faciliter l'acheminement</h2>
                <p class="mt-2 text-xs leading-5 text-slate-500">
                    La plateforme est conçue pour prendre en compte les commandes pouvant circuler entre différentes villes
                    et passer par des agences.
                </p>
            </article>
        </section>

        <section class="mt-8 grid gap-8 lg:grid-cols-[1.1fr_.9fr]">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.18em] text-orange-600">Notre vision</span>
                <h2 class="mt-2 text-2xl font-black">Rendre le commerce en ligne plus rassurant.</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                    <p>
                        Dans une marketplace classique, l'acheteur doit souvent faire confiance au vendeur avant de recevoir
                        son produit. Ali-Kamer cherche à réduire cette difficulté en structurant le parcours de commande
                        autour de la protection de la transaction.
                    </p>
                    <p>
                        Le système prévoit également des états de commande permettant de suivre l'évolution d'une commande,
                        depuis le paiement jusqu'à l'acheminement, la réception et la validation.
                    </p>
                    <p>
                        Cette approche permet de créer un environnement dans lequel acheteurs, vendeurs et acteurs
                        logistiques peuvent intervenir avec des responsabilités mieux définies.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="text-base font-black">Les principes d'Ali-Kamer</h2>
                <div class="mt-4 space-y-3">
                    <div class="flex gap-3 rounded-xl bg-white p-3">
                        <span class="text-lg">🛡️</span>
                        <div>
                            <p class="text-xs font-black">Protection acheteur</p>
                            <p class="mt-0.5 text-[10px] text-slate-500">La réception et la validation font partie du
                                parcours.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-xl bg-white p-3">
                        <span class="text-lg">💳</span>
                        <div>
                            <p class="text-xs font-black">Paiement structuré</p>
                            <p class="mt-0.5 text-[10px] text-slate-500">Les paiements sont intégrés au cycle de commande.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-xl bg-white p-3">
                        <span class="text-lg">📍</span>
                        <div>
                            <p class="text-xs font-black">Commerce local</p>
                            <p class="mt-0.5 text-[10px] text-slate-500">Une expérience adaptée au contexte camerounais.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 rounded-xl bg-white p-3">
                        <span class="text-lg">🤝</span>
                        <div>
                            <p class="text-xs font-black">Confiance</p>
                            <p class="mt-0.5 text-[10px] text-slate-500">Un cadre commun pour acheteurs et vendeurs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-8 rounded-2xl border border-orange-200 bg-orange-50 p-6">
            <h2 class="text-lg font-black">Commencer sur Ali-Kamer</h2>
            <p class="mt-2 max-w-2xl text-xs leading-5 text-slate-600">
                Découvrez les produits depuis l'accueil. Si vous souhaitez passer une commande et accéder à votre espace
                personnel, connectez-vous ou créez un compte.
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('buyer.home') }}"
                    class="rounded-lg bg-orange-500 px-4 py-2.5 text-xs font-bold text-white">Voir les produits</a>
                @guest
                    <a href="{{ route('register.show') }}"
                        class="rounded-lg border border-orange-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700">Créer
                        un compte</a>
                {{-- @else
                <a href="{{ route('dashboard') }}" class="rounded-lg border border-orange-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700">Mon tableau de bord</a>
            @endguest --}} @endguest
            </div>
        </section>
    </div>
@endsection
