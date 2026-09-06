@php
    $layout = auth()->user()->isBuyer()
        ? 'layouts.buyer'
        : 'layouts.seller';
@endphp

@extends($layout)
@section('title', 'Aide — Ali-Kamer')
@section('meta_description', 'Centre d’aide Ali-Kamer : compte, recherche, commande, paiement séquestre, livraison et
    validation.')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span
                class="inline-block rounded-full bg-[#00843D]/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#00843D]">Centre
                d'aide</span>
            <h1 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl text-slate-950">Comment utiliser Ali-Kamer ?</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                Les principales étapes pour trouver un produit, commander, suivre la livraison et comprendre la protection
                de votre paiement.
            </p>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition">
                <span class="text-xl">👤</span>
                <h2 class="mt-3 text-base font-black text-slate-900">1. Créer un compte ou se connecter</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Utilisez « Se connecter » si vous avez déjà un compte. Sinon, créez votre compte. Une fois connecté,
                    l'accès à votre espace et à votre tableau de bord est disponible depuis le header.
                </p>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('login') }}"
                        class="rounded-lg bg-slate-900 hover:bg-slate-800 px-3 py-2 text-xs font-bold text-white transition">Se
                        connecter</a>
                    <a href="{{ route('register.show') }}"
                        class="rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 transition">Créer
                        un compte</a>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition">
                <span class="text-xl">🔎</span>
                <h2 class="mt-3 text-base font-black text-slate-900">2. Rechercher un produit</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Utilisez la recherche du header ou parcourez les catégories. Les résultats restent accessibles avec
                    leurs informations de prix, vendeur, localisation, stock et transport lorsqu'elles sont disponibles.
                </p>
                <a href="{{ route('buyer.home') }}"
                    class="mt-4 inline-block text-xs font-black text-[#00843D] hover:underline">Explorer les produits →</a>
            </article>

            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition">
                <span class="text-xl">🛍️</span>
                <h2 class="mt-3 text-base font-black text-slate-900">3. Choisir un produit</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Ouvrez la fiche d'un produit pour consulter les informations disponibles avant de poursuivre votre
                    commande.
                </p>
            </article>

            <article id="securite"
                class="rounded-2xl border border-emerald-300 bg-[#F3FBF6] p-5 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2 h-full bg-[#00843D]"></div>
                <span class="text-xl">🔐</span>
                <h2 class="mt-3 text-base font-black text-slate-900">4. Comprendre le paiement séquestre</h2>
                <p class="mt-2 text-xs leading-5 text-slate-700">
                    Le principe d'Ali-Kamer est de protéger la transaction : le paiement est conservé pendant le processus
                    de livraison et le vendeur n'est pas simplement payé comme si la commande était déjà validée. La
                    validation de la réception joue donc un rôle central.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition">
                <span class="text-xl">🚚</span>
                <h2 class="mt-3 text-base font-black text-slate-900">5. Livraison entre les villes</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Ali-Kamer intègre un parcours adapté aux commandes pouvant nécessiter un acheminement entre villes et le
                    passage par des agences ou points de traitement.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition">
                <span class="text-xl">📦</span>
                <h2 class="mt-3 text-base font-black text-slate-900">6. Réception et validation</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    À la réception, le parcours de commande permet de confirmer la livraison. Cette étape participe au
                    mécanisme de protection de la transaction.
                </p>
            </article>

            <article class="rounded-2xl border border-red-200 bg-red-50/40 p-5 shadow-sm">
                <span class="text-xl">⚠️</span>
                <h2 class="mt-3 text-base font-black text-[#CE1126]">En cas de problème</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Si une commande pose problème, utilisez les fonctionnalités de litige prévues dans votre espace lorsque
                    celles-ci sont disponibles pour votre commande. Évitez de déplacer la transaction hors de la plateforme.
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition">
                <span class="text-xl">🏪</span>
                <h2 class="mt-3 text-base font-black text-slate-900">Vous voulez vendre ?</h2>
                <p class="mt-2 text-xs leading-5 text-slate-600">
                    Le vendeur dispose de son propre espace de gestion pour présenter ses produits et suivre son activité.
                    Connectez-vous ou créez un compte pour accéder aux fonctionnalités disponibles selon votre rôle.
                </p>
            </article>
        </div>

        <div class="mt-8 rounded-2xl bg-slate-950 p-6 text-white border-l-4 border-[#FCD116]">
            <h2 class="text-lg font-black">Besoin d'aller directement à votre espace ?</h2>
            <p class="mt-2 text-xs leading-5 text-slate-400">Le bouton « Tableau de bord » apparaît dans le header lorsque
                vous êtes connecté.</p>
        </div>
    </div>
@endsection
