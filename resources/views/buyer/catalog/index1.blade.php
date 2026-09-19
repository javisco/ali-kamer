@extends('base')

@section('title', 'Accueil - Produits')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-slate-50 min-h-screen">


        {{-- =========================================================
    HERO PREMIUM ALI-KAMER
========================================================= --}}

        <section
            class="relative overflow-hidden rounded-[32px]
           bg-gradient-to-br from-primary-900 via-primary-700 to-primary-500
           shadow-2xl">

            {{-- Décor --}}
            <div class="absolute -top-24 -left-20 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-20 w-96 h-96 rounded-full bg-primary-300/10 blur-3xl"></div>

            <div class="relative z-10 grid lg:grid-cols-2 gap-10 items-center px-8 py-14 lg:px-14 lg:py-20">

                {{-- ==========================
            Colonne gauche
        =========================== --}}
                <div>

                    <span
                        class="inline-flex items-center gap-2
                       rounded-full bg-white/15 backdrop-blur
                       border border-white/20
                       px-4 py-2 text-sm text-primary-100">

                        🛡️ Marketplace sécurisée

                    </span>

                    <h1
                        class="mt-6 text-4xl md:text-6xl
                       font-black text-white
                       leading-tight">

                        Achetez en toute
                        <span class="text-primary-300">
                            confiance.
                        </span>

                    </h1>

                    <p class="mt-6 text-lg text-primary-100
                       leading-8 max-w-xl">

                        Votre paiement reste protégé jusqu'à la réception
                        de votre commande.

                        Achetez auprès de vendeurs vérifiés partout
                        au Cameroun sans craindre les arnaques.

                    </p>

                    {{-- Recherche --}}

                    <form action="{{ route('buyer.home') }}" method="GET" class="mt-8">

                        <div
                            class="flex flex-col sm:flex-row
                           bg-white rounded-2xl
                           shadow-xl overflow-hidden">

                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Que recherchez-vous aujourd'hui ?"
                                class="flex-1
                               px-6 py-5
                               text-slate-700
                               text-base
                               outline-none">

                            <button
                                class="bg-primary-600
                               hover:bg-primary-700
                               px-8
                               font-bold
                               text-white
                               transition">

                                Rechercher

                            </button>

                        </div>

                    </form>

                    {{-- Avantages --}}

                    <div class="grid grid-cols-2
                       gap-4
                       mt-10">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-12 h-12 rounded-xl
                               bg-white/15
                               flex items-center justify-center">

                                🔒

                            </div>

                            <div>

                                <div class="font-bold text-white">

                                    Paiement sécurisé

                                </div>

                                <div class="text-sm text-primary-100">

                                    Argent protégé

                                </div>

                            </div>

                        </div>

                        <div class="flex items-center gap-3">

                            <div
                                class="w-12 h-12 rounded-xl
                               bg-white/15
                               flex items-center justify-center">

                                🚚

                            </div>

                            <div>

                                <div class="font-bold text-white">

                                    Livraison

                                </div>

                                <div class="text-sm text-primary-100">

                                    Partout au Cameroun

                                </div>

                            </div>

                        </div>

                        <div class="flex items-center gap-3">

                            <div
                                class="w-12 h-12 rounded-xl
                               bg-white/15
                               flex items-center justify-center">

                                ⭐

                            </div>

                            <div>

                                <div class="font-bold text-white">

                                    Vendeurs vérifiés

                                </div>

                                <div class="text-sm text-primary-100">

                                    Plus de confiance

                                </div>

                            </div>

                        </div>

                        <div class="flex items-center gap-3">

                            <div
                                class="w-12 h-12 rounded-xl
                               bg-white/15
                               flex items-center justify-center">

                                💬

                            </div>

                            <div>

                                <div class="font-bold text-white">

                                    Assistance

                                </div>

                                <div class="text-sm text-primary-100">

                                    Nous vous aidons

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- ==========================
            Colonne droite
        =========================== --}}

                <div class="hidden lg:flex justify-center">

                    <div class="relative w-full max-w-md">

                        {{-- Carte principale --}}
                        <div
                            class="rounded-3xl
                           bg-white
                           shadow-2xl
                           p-8">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-sm text-slate-500">

                                        Paiement sécurisé

                                    </p>

                                    <h3
                                        class="text-2xl
                                       font-black
                                       text-slate-900">

                                        Séquestre

                                    </h3>

                                </div>

                                <div
                                    class="w-16 h-16
                                   rounded-2xl
                                   bg-primary-100
                                   flex items-center justify-center
                                   text-3xl">

                                    🛡️

                                </div>

                            </div>

                            <div class="mt-8 space-y-5">

                                <div class="flex items-center gap-4">

                                    <div
                                        class="w-10 h-10 rounded-full
                                       bg-primary-600 text-white
                                       flex items-center justify-center
                                       font-bold">

                                        1

                                    </div>

                                    <div>

                                        <div class="font-semibold">

                                            Vous payez

                                        </div>

                                        <div class="text-sm text-slate-500">

                                            Votre argent est sécurisé.

                                        </div>

                                    </div>

                                </div>

                                <div class="flex items-center gap-4">

                                    <div
                                        class="w-10 h-10 rounded-full
                                       bg-primary-600 text-white
                                       flex items-center justify-center
                                       font-bold">

                                        2

                                    </div>

                                    <div>

                                        <div class="font-semibold">

                                            Le vendeur expédie

                                        </div>

                                        <div class="text-sm text-slate-500">

                                            Livraison suivie.

                                        </div>

                                    </div>

                                </div>

                                <div class="flex items-center gap-4">

                                    <div
                                        class="w-10 h-10 rounded-full
                                       bg-success text-white
                                       flex items-center justify-center
                                       font-bold">

                                        3

                                    </div>

                                    <div>

                                        <div class="font-semibold">

                                            Vous confirmez

                                        </div>

                                        <div class="text-sm text-slate-500">

                                            Le vendeur est payé.

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- Carte flottante --}}
                        <div
                            class="absolute
                           -bottom-6
                           -left-10
                           rounded-2xl
                           bg-white
                           shadow-xl
                           px-6 py-4">

                            <div
                                class="text-success
                               font-black
                               text-2xl">

                                100%

                            </div>

                            <div class="text-sm
                               text-slate-500">

                                Paiement protégé

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>
        {{-- =========================================================
    CATÉGORIES PREMIUM
========================================================= --}}

        <section class="mt-14">

            {{-- En-tête --}}
            <div class="flex items-center justify-between mb-8">

                <div>
                    <p class="text-primary-600 font-bold uppercase tracking-widest text-xs">
                        Explorer
                    </p>

                    <h2 class="text-3xl font-black text-slate-900 mt-1">
                        Parcourir les catégories
                    </h2>

                    <p class="text-slate-500 mt-2">
                        Trouvez rapidement ce que vous recherchez parmi toutes nos catégories.
                    </p>
                </div>

                <a href="#"
                    class="hidden md:flex items-center gap-2
                   text-primary-600 font-bold hover:text-primary-700 transition">

                    Voir toutes

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />

                    </svg>

                </a>

            </div>

            {{-- Grille --}}
            <div
                class="grid
                grid-cols-2
                md:grid-cols-3
                xl:grid-cols-4
                gap-6">

                {{-- Electronique --}}
                <a href="#"
                    class="group bg-white rounded-3xl
                   border border-slate-200
                   p-7
                   hover:border-primary-500
                   hover:-translate-y-2
                   hover:shadow-2xl
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-primary-100
                        flex items-center justify-center
                        text-4xl">

                        📱

                    </div>

                    <h3 class="mt-6 font-black text-xl text-slate-900">

                        Électronique

                    </h3>

                    <p class="text-slate-500 mt-2">

                        Smartphones, ordinateurs,
                        téléviseurs...

                    </p>

                    <div class="mt-6 text-primary-600 font-bold">

                        Explorer →

                    </div>

                </a>

                {{-- Mode --}}

                <a href="#"
                    class="group bg-white rounded-3xl
                   border border-slate-200
                   p-7
                   hover:border-accent-500
                   hover:-translate-y-2
                   hover:shadow-2xl
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-accent-100
                        flex items-center justify-center
                        text-4xl">

                        👕

                    </div>

                    <h3 class="mt-6 font-black text-xl">

                        Mode

                    </h3>

                    <p class="text-slate-500 mt-2">

                        Hommes,
                        femmes,
                        enfants.

                    </p>

                    <div class="mt-6 text-accent-600 font-bold">

                        Explorer →

                    </div>

                </a>

                {{-- Maison --}}

                <a href="#"
                    class="group bg-white rounded-3xl
                   border border-slate-200
                   p-7
                   hover:border-warning
                   hover:-translate-y-2
                   hover:shadow-2xl
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-warning-100
                        flex items-center justify-center
                        text-4xl">

                        🛋️

                    </div>

                    <h3 class="mt-6 font-black text-xl">

                        Maison

                    </h3>

                    <p class="text-slate-500 mt-2">

                        Meubles,
                        décoration,
                        électroménager.

                    </p>

                    <div class="mt-6 text-warning-600 font-bold">

                        Explorer →

                    </div>

                </a>

                {{-- Agriculture --}}

                <a href="#"
                    class="group bg-white rounded-3xl
                   border border-slate-200
                   p-7
                   hover:border-success
                   hover:-translate-y-2
                   hover:shadow-2xl
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-success-100
                        flex items-center justify-center
                        text-4xl">

                        🌾

                    </div>

                    <h3 class="mt-6 font-black text-xl">

                        Agriculture

                    </h3>

                    <p class="text-slate-500 mt-2">

                        Matériel,
                        semences,
                        élevage.

                    </p>

                    <div class="mt-6 text-success font-bold">

                        Explorer →

                    </div>

                </a>

                {{-- Automobile --}}

                <a href="#"
                    class="group bg-white rounded-3xl
                   border border-slate-200
                   p-7
                   hover:border-danger
                   hover:-translate-y-2
                   hover:shadow-2xl
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-danger-100
                        flex items-center justify-center
                        text-4xl">

                        🚗

                    </div>

                    <h3 class="mt-6 font-black text-xl">

                        Automobile

                    </h3>

                    <p class="text-slate-500 mt-2">

                        Pièces,
                        accessoires,
                        équipements.

                    </p>

                    <div class="mt-6 text-danger font-bold">

                        Explorer →

                    </div>

                </a>

                {{-- Beauté --}}

                <a href="#"
                    class="group bg-white rounded-3xl
                   border border-slate-200
                   p-7
                   hover:border-accent-500
                   hover:-translate-y-2
                   hover:shadow-2xl
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-accent-100
                        flex items-center justify-center
                        text-4xl">

                        💄

                    </div>

                    <h3 class="mt-6 font-black text-xl">

                        Beauté

                    </h3>

                    <p class="text-slate-500 mt-2">

                        Cosmétiques,
                        parfums,
                        soins.

                    </p>

                    <div class="mt-6 text-accent-600 font-bold">

                        Explorer →

                    </div>

                </a>

                {{-- Cuisine --}}

                <a href="#"
                    class="group bg-white rounded-3xl
                   border border-slate-200
                   p-7
                   hover:border-accent-500
                   hover:-translate-y-2
                   hover:shadow-2xl
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-accent-100
                        flex items-center justify-center
                        text-4xl">

                        🍳

                    </div>

                    <h3 class="mt-6 font-black text-xl">

                        Cuisine

                    </h3>

                    <p class="text-slate-500 mt-2">

                        Ustensiles,
                        équipements,
                        vaisselle.

                    </p>

                    <div class="mt-6 text-accent-600 font-bold">

                        Explorer →

                    </div>

                </a>

                {{-- Toutes les catégories --}}

                <a href="#"
                    class="group rounded-3xl
                   border-2 border-dashed
                   border-primary-300
                   bg-primary-50
                   p-7
                   hover:bg-primary-600
                   hover:border-primary-600
                   transition">

                    <div
                        class="w-16 h-16 rounded-2xl
                        bg-white
                        flex items-center justify-center
                        text-4xl">

                        ➕

                    </div>

                    <h3 class="mt-6 font-black text-xl
                       group-hover:text-white">

                        Toutes les catégories

                    </h3>

                    <p class="text-slate-500 mt-2
                      group-hover:text-primary-100">

                        Découvrez tous nos univers.

                    </p>

                </a>

            </div>

        </section>
        {{-- =========================================================
    PROMOTIONS / OFFRES PREMIUM
========================================================= --}}

        <section class="mt-20">

            <div class="flex items-center justify-between mb-8">

                <div>

                    <p class="text-primary-600 uppercase tracking-widest font-bold text-xs">

                        À ne pas manquer

                    </p>

                    <h2 class="text-3xl font-black text-slate-900 mt-1">

                        Promotions du moment

                    </h2>

                </div>

                <a href="#" class="text-primary-600 font-bold hover:text-primary-700">

                    Voir toutes →

                </a>

            </div>


            <div class="grid lg:grid-cols-3 gap-6">

                {{-- Grande Bannière --}}

                <a href="#"
                    class="lg:col-span-2 relative overflow-hidden rounded-3xl
                   bg-gradient-to-r from-primary-700 via-primary-600 to-primary-500
                   p-10 text-white shadow-xl group">

                    <div class="absolute right-0 top-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                    <div class="relative z-10 max-w-lg">

                        <span
                            class="inline-block
                           bg-white/20
                           px-4 py-2
                           rounded-full
                           text-sm">

                            🔥 Offre spéciale

                        </span>

                        <h3
                            class="mt-6
                           text-4xl
                           font-black
                           leading-tight">

                            Jusqu'à

                            <span class="text-warning">

                                -50%

                            </span>

                            sur une sélection
                            de produits.

                        </h3>

                        <p class="mt-6
                           text-primary-100
                           leading-8">

                            Smartphones,
                            électroménager,
                            vêtements,
                            équipements agricoles
                            et bien plus encore.

                        </p>

                        <button
                            class="mt-8
                           bg-white
                           text-primary-700
                           px-8
                           py-4
                           rounded-2xl
                           font-bold
                           shadow-lg
                           group-hover:scale-105
                           transition">

                            Découvrir

                        </button>

                    </div>

                </a>

                {{-- Carte 1 --}}

                <a href="#"
                    class="rounded-3xl
                   bg-gradient-to-br
                   from-accent-500
                   to-danger
                   p-8
                   text-white
                   shadow-xl
                   hover:scale-[1.02]
                   transition">

                    <div class="text-5xl">

                        🚚

                    </div>

                    <h3 class="mt-6
                       text-2xl
                       font-black">

                        Livraison
                        rapide

                    </h3>

                    <p class="mt-4
                       text-accent-100">

                        Faites livrer
                        partout au Cameroun.

                    </p>

                </a>

            </div>


            <div class="grid md:grid-cols-3 gap-6 mt-6">

                <a href="#" class="bg-white rounded-3xl border border-slate-200 p-7 hover:shadow-xl transition">

                    <div class="text-4xl">

                        💻

                    </div>

                    <h3 class="mt-5 font-black text-xl">

                        Électronique

                    </h3>

                    <p class="mt-3 text-slate-500">

                        Les meilleurs prix
                        sur les smartphones
                        et ordinateurs.

                    </p>

                </a>


                <a href="#" class="bg-white rounded-3xl border border-slate-200 p-7 hover:shadow-xl transition">

                    <div class="text-4xl">

                        👟

                    </div>

                    <h3 class="mt-5 font-black text-xl">

                        Mode

                    </h3>

                    <p class="mt-3 text-slate-500">

                        Découvrez les
                        nouvelles collections.

                    </p>

                </a>


                <a href="#" class="bg-white rounded-3xl border border-slate-200 p-7 hover:shadow-xl transition">

                    <div class="text-4xl">

                        🌾

                    </div>

                    <h3 class="mt-5 font-black text-xl">

                        Agriculture

                    </h3>

                    <p class="mt-3 text-slate-500">

                        Tout le matériel
                        nécessaire pour
                        vos exploitations.

                    </p>

                </a>

            </div>

        </section>
        {{-- =========================================================
    PRODUITS TENDANCE
========================================================= --}}

        <section class="mt-20">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8">

                <div>

                    <p class="text-primary-600 font-bold uppercase tracking-[0.3em] text-xs">

                        Les meilleures offres

                    </p>

                    <h2 class="mt-2 text-4xl font-black text-slate-900">

                        ⭐ Produits tendance

                    </h2>

                    <p class="mt-3 text-slate-500">

                        Les produits les plus consultés par nos visiteurs.

                    </p>

                </div>

                <a href="{{ route('buyer.home') }}"
                    class="mt-4 md:mt-0
                   inline-flex items-center gap-2
                   font-bold text-primary-600 hover:text-primary-700">

                    Voir tous les produits

                    →

                </a>

            </div>




            {{-- Section Recherche Interactive --}}
            <div class="mb-8">
                <form method="GET" action="{{ route('buyer.home') }}" class="max-w-2xl mx-auto">
                    <div class="relative flex items-center group">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Rechercher un produit, une ville..."
                            class="w-full pl-11 pr-28 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-600 shadow-sm transition-all duration-200">

                        <button type="submit"
                            class="absolute right-1.5 bg-primary-600 hover:bg-primary-700 active:scale-95 text-white font-semibold px-5 py-2 rounded-xl text-xs transition-all duration-200 shadow-md shadow-primary-500/20">
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>

            {{-- En-tête des résultats --}}
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-200">
                <h1 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    @if (request('q'))
                        Résultats pour <span class="text-primary-600 font-extrabold">"{{ request('q') }}"</span>
                    @else
                        Dernières annonces
                    @endif
                </h1>
                <span
                    class="text-xs font-bold text-slate-600 bg-slate-200/70 border border-slate-300/50 px-3 py-1 rounded-full">
                    {{ $products->total() }} {{ Str::plural('produit', $products->total()) }}
                </span>
            </div>

            {{-- Grille Produits --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @forelse($products as $product)
                    <a href="{{ route('product.show', $product) }}"
                        class="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-primary-400/60 hover:-translate-y-1.5 transition-all duration-300 ease-out flex flex-col justify-between">

                        <div>
                            {{-- Zone Image --}}
                            <div class="w-full h-48 bg-slate-100 overflow-hidden border-b border-slate-100 relative">
                                @if ($product->images->first())
                                    <img src="{{ Storage::url($product->images->first()->url) }}"
                                        alt="{{ $product->title }}"
                                        class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500 ease-out">
                                @else
                                    <div
                                        class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-1 bg-slate-50">
                                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-[11px] font-medium text-slate-400">Pas d'image</span>
                                    </div>
                                @endif

                                {{-- Tag Transport (Inclus ou Non Inclus) sur l'image --}}
                                @if ($product->shipping_included)
                                    <span
                                        class="absolute top-2.5 left-2.5 z-10 bg-success/95 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                                        Transport inclus
                                    </span>
                                @else
                                    <span
                                        class="absolute top-2.5 left-2.5 z-10 bg-slate-700/80 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm uppercase tracking-wider">
                                        Transport non inclus
                                    </span>
                                @endif
                            </div>

                            {{-- Contenu de la Carte --}}
                            <div class="px-4 py-3">
                                {{-- Titre --}}
                                <h2
                                    class="text-sm font-bold text-slate-800 group-hover:text-primary-600 transition-colors duration-200 line-clamp-1 leading-snug">
                                    {{ $product->title }}
                                </h2>

                                {{-- Ville / Localisation --}}
                                <p class="text-[11px] font-medium text-slate-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $product->city ?? 'Ville non précisée' }}</span>
                                </p>

                                {{-- Prix + Ancien Prix + Taux de Réduction en Rouge --}}
                                <div class="mt-3 flex items-center gap-2 flex-wrap">
                                    <span class="text-base font-extrabold text-success">
                                        {{ number_format($product->price, 0, ',', ' ') }} <span
                                            class="text-[11px] font-bold">FCFA</span>
                                    </span>

                                    @php
                                        $hasDiscount =
                                            (method_exists($product, 'hasDiscount') && $product->hasDiscount()) ||
                                            ($product->old_price && $product->old_price > $product->price);
                                    @endphp

                                    @if ($hasDiscount)
                                        {{-- Ancien Prix Barré --}}
                                        <span class="text-[11px] font-medium text-slate-400 line-through">
                                            {{ number_format($product->old_price, 0, ',', ' ') }}
                                        </span>

                                        {{-- Pourcentage de Réduction en Rouge --}}
                                        @php
                                            $discountPercent = round(
                                                (($product->old_price - $product->price) / $product->old_price) * 100,
                                            );
                                        @endphp
                                        <span
                                            class="text-[10px] font-black text-danger bg-danger-50 border border-danger-200 px-1.5 py-0.5 rounded">
                                            -{{ $discountPercent }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Bouton d'action interactif --}}
                        <div class="p-4 pt-0 mt-2">
                            <span
                                class="w-full flex items-center justify-center gap-1.5 bg-primary-600 group-hover:bg-primary-700 text-white font-semibold py-2 rounded-xl text-xs transition-all duration-200 shadow-sm shadow-primary-500/10">
                                <span>Voir le produit</span>
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </div>

                    </a>
                @empty
                    <div
                        class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                        <div
                            class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 font-semibold">Aucun produit trouvé.</p>
                        @if (request('q'))
                            <a href="{{ route('buyer.home') }}"
                                class="inline-block mt-2 text-xs text-primary-600 font-bold hover:underline">
                                Réinitialiser la recherche
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($products->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
    </div>
@endsection
