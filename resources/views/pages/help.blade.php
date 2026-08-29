{{-- resources/views/pages/help.blade.php --}}
@extends('base')

@section('title', 'Aide — Ali-Kamer')
@section('meta_description', 'Centre d’aide Ali-Kamer : compte, recherche, commande, paiement séquestre, livraison et validation.')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="max-w-3xl">
        <span class="text-[10px] font-black uppercase tracking-[0.18em] text-orange-600">Centre d'aide</span>
        <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Comment utiliser Ali-Kamer ?</h1>
        <p class="mt-3 text-sm leading-6 text-slate-500">
            Les principales étapes pour trouver un produit, commander, suivre la livraison et comprendre la protection de votre paiement.
        </p>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <span class="text-xl">👤</span>
            <h2 class="mt-3 text-base font-black">1. Créer un compte ou se connecter</h2>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Utilisez « Se connecter » si vous avez déjà un compte. Sinon, créez votre compte. Une fois connecté, l'accès à votre espace et à votre tableau de bord est disponible depuis le header.
            </p>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('login') }}" class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Se connecter</a>
                <a href="{{ route('register.show') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700">Créer un compte</a>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <span class="text-xl">🔎</span>
            <h2 class="mt-3 text-base font-black">2. Rechercher un produit</h2>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Utilisez la recherche du header ou parcourez les catégories. Les résultats restent accessibles avec leurs informations de prix, vendeur, localisation, stock et transport lorsqu'elles sont disponibles.
            </p>
            <a href="{{ route('buyer.home') }}" class="mt-4 inline-block text-xs font-bold text-orange-600 hover:underline">Explorer les produits →</a>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <span class="text-xl">🛍️</span>
            <h2 class="mt-3 text-base font-black">3. Choisir un produit</h2>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Ouvrez la fiche d'un produit pour consulter les informations disponibles avant de poursuivre votre commande.
            </p>
        </article>

        <article id="securite" class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-5">
            <span class="text-xl">🔐</span>
            <h2 class="mt-3 text-base font-black text-slate-900">4. Comprendre le paiement séquestre</h2>
            <p class="mt-2 text-xs leading-5 text-slate-600">
                Le principe d'Ali-Kamer est de protéger la transaction : le paiement est conservé pendant le processus de livraison et le vendeur n'est pas simplement payé comme si la commande était déjà validée. La validation de la réception joue donc un rôle central.
            </p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <span class="text-xl">🚚</span>
            <h2 class="mt-3 text-base font-black">5. Livraison entre les villes</h2>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Ali-Kamer intègre un parcours adapté aux commandes pouvant nécessiter un acheminement entre villes et le passage par des agences ou points de traitement.
            </p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <span class="text-xl">📦</span>
            <h2 class="mt-3 text-base font-black">6. Réception et validation</h2>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                À la réception, le parcours de commande permet de confirmer la livraison. Cette étape participe au mécanisme de protection de la transaction.
            </p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <span class="text-xl">⚠️</span>
            <h2 class="mt-3 text-base font-black">En cas de problème</h2>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Si une commande pose problème, utilisez les fonctionnalités de litige prévues dans votre espace lorsque celles-ci sont disponibles pour votre commande. Évitez de déplacer la transaction hors de la plateforme.
            </p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <span class="text-xl">🏪</span>
            <h2 class="mt-3 text-base font-black">Vous voulez vendre ?</h2>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Le vendeur dispose de son propre espace de gestion pour présenter ses produits et suivre son activité. Connectez-vous ou créez un compte pour accéder aux fonctionnalités disponibles selon votre rôle.
            </p>
            {{-- @guest
                <a href="{{ route('register') }}" class="mt-4 inline-block rounded-lg bg-orange-500 px-3 py-2 text-xs font-bold text-white">Créer mon compte</a>
            @else
                <a href="{{ route('dashboard') }}" class="mt-4 inline-block rounded-lg bg-slate-900 px-3 py-2 text-xs font-bold text-white">Ouvrir mon tableau de bord</a>
            @endguest --}}
        </article>
    </div>

    <div class="mt-8 rounded-2xl bg-slate-950 p-6 text-white">
        <h2 class="text-lg font-black">Besoin d'aller directement à votre espace ?</h2>
        <p class="mt-2 text-xs leading-5 text-slate-400">Le bouton « Tableau de bord » apparaît dans le header lorsque vous êtes connecté.</p>
        {{-- <div class="mt-4">
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex rounded-lg bg-orange-500 px-4 py-2.5 text-xs font-bold text-white">Accéder au tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="inline-flex rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-slate-900">Se connecter</a>
            @endauth
        </div> --}}
    </div>
</div>
@endsection
