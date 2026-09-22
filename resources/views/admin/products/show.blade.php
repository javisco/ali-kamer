@extends('layouts.admin')

@section('title', 'Gestion du produit')

@section('content')

    <div x-data="{
        modal: null
    }" class="min-h-screen bg-gray-50 py-8">


        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Messages --}}
            @if (session('success'))
                <div class="mb-5 rounded-xl bg-green-50 border border-green-200 text-green-700 px-5 py-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-4">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">

                <div>

                    <a href="{{ route('admin.products.moderation') }}" class="text-sm text-gray-500 hover:text-[#016837]">
                        ← Retour à la modération
                    </a>

                    <h1 class="text-2xl font-bold text-gray-900 mt-2">
                        {{ $product->title }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Produit #{{ $product->id }}
                    </p>

                </div>

                <span
                    class="px-3 py-2 rounded-full text-sm font-bold
            {{ $product->status === 'visible'
                ? 'bg-green-100 text-green-700'
                : ($product->status === 'banned'
                    ? 'bg-red-100 text-red-700'
                    : 'bg-yellow-100 text-yellow-700') }}">
                    {{ strtoupper($product->status) }}
                </span>

            </div>

            {{-- Produit --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">

                    {{-- Informations produit --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">

                        <h2 class="font-bold text-lg text-gray-900 mb-5">
                            Informations du produit
                        </h2>

                        <div class="grid grid-cols-2 gap-5">

                            <div>
                                <p class="text-xs text-gray-500">Titre</p>
                                <p class="font-semibold text-gray-900 mt-1">
                                    {{ $product->title }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">Prix</p>
                                <p class="font-semibold text-[#016837] mt-1">
                                    {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">Stock</p>
                                <p class="font-semibold text-gray-900 mt-1">
                                    {{ $product->stock }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">Catégorie</p>
                                <p class="font-semibold text-gray-900 mt-1">
                                    {{ $product->category?->name ?? '—' }}
                                </p>
                            </div>

                        </div>

                        @if ($product->description)
                            <div class="mt-6">

                                <p class="text-xs text-gray-500">
                                    Description
                                </p>

                                <div class="mt-2 text-sm text-gray-700 whitespace-pre-line">
                                    {{ $product->description }}
                                </div>

                            </div>
                        @endif

                    </div>

                    {{-- Images --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">

                        <h2 class="font-bold text-lg text-gray-900 mb-5">
                            Images
                        </h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                            @forelse($product->images as $image)
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100">

                                    <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $product->title }}"
                                        class="w-full h-full object-cover">

                                </div>

                            @empty

                                <p class="text-sm text-gray-500">
                                    Aucune image.
                                </p>
                            @endforelse

                        </div>

                    </div>

                    {{-- Boutique --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <h2 class="font-bold text-lg text-gray-900">
                                    Boutique concernée
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $product->shop?->name ?? 'Boutique inconnue' }}
                                </p>

                            </div>

                            @if ($product->shop)
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-semibold">
                                    {{ $product->shop->status }}
                                </span>
                            @endif

                        </div>

                        @if ($product->shop)

                            <div class="grid grid-cols-2 gap-5 mt-6">

                                <div>
                                    <p class="text-xs text-gray-500">Ville</p>
                                    <p class="font-medium mt-1">
                                        {{ $product->shop->city ?? '—' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">Téléphone</p>
                                    <p class="font-medium mt-1">
                                        {{ $product->shop->phone ?? '—' }}
                                    </p>
                                </div>

                            </div>

                            <div class="flex flex-wrap gap-2 mt-6">

                                @if ($product->shop->status === 'active')
                                    <button type="button" @click="modal = 'suspend-shop'"
                                        class="px-4 py-2 rounded-lg bg-yellow-500 text-white font-semibold">
                                        Suspendre
                                    </button>
                                @elseif($product->shop->status === 'suspended')
                                    <form method="POST"
                                        action="{{ route('admin.products.shop.activate', $product->shop) }}">
                                        @csrf
                                        <button class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold">
                                            Activer
                                        </button>
                                    </form>
                                @endif

                                @if ($product->shop->status !== 'banned')
                                    <button type="button" @click="modal = 'ban-shop'"
                                        class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold">
                                        Bannir la boutique
                                    </button>
                                @endif

                            </div>

                        @endif

                    </div>

                    {{-- Vendeur --}}
                    @if ($product->shop?->user)

                        <div class="bg-white rounded-2xl border border-gray-200 p-6">

                            <div class="flex items-start justify-between">

                                <div>

                                    <h2 class="font-bold text-lg text-gray-900">
                                        Vendeur
                                    </h2>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $product->shop->user->name }}
                                    </p>

                                </div>

                                <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-semibold">
                                    {{ $product->shop->user->status }}
                                </span>

                            </div>

                            <div class="grid grid-cols-2 gap-5 mt-6">

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Email
                                    </p>

                                    <p class="font-medium mt-1">
                                        {{ $product->shop->user->email }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Téléphone
                                    </p>

                                    <p class="font-medium mt-1">
                                        {{ $product->shop->user->phone ?? '—' }}
                                    </p>
                                </div>

                            </div>

                            <div class="flex flex-wrap gap-2 mt-6">

                                @if ($product->shop->user->status === 'active')
                                    <button type="button" @click="modal = 'suspend-seller'"
                                        class="px-4 py-2 rounded-lg bg-yellow-500 text-white font-semibold">
                                        Suspendre le vendeur
                                    </button>
                                @elseif($product->shop->user->status === 'suspended')
                                    <form method="POST"
                                        action="{{ route('admin.products.seller.activate', $product->shop->user) }}">
                                        @csrf

                                        <button class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold">
                                            Activer le vendeur
                                        </button>
                                    </form>
                                @endif

                                @if ($product->shop->user->status !== 'banned')
                                    <button type="button" @click="modal = 'ban-seller'"
                                        class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold">
                                        Bannir le vendeur
                                    </button>
                                @endif

                            </div>

                        </div>

                    @endif

                </div>

                {{-- Colonne actions --}}
                <div class="space-y-6">

                    <div class="bg-white rounded-2xl border border-gray-200 p-6">

                        <h2 class="font-bold text-lg text-gray-900 mb-5">
                            Actions administrateur
                        </h2>

                        <div class="space-y-3">

                            @if ($product->status === 'visible')

                                <form method="POST" action="{{ route('admin.products.hide', $product) }}">
                                    @csrf

                                    <button class="w-full px-4 py-3 rounded-xl bg-yellow-500 text-white font-semibold">
                                        Masquer le produit
                                    </button>
                                </form>
                            @elseif($product->status === 'hidden')
                                @if ($product->shop?->status === 'active')
                                    <form method="POST" action="{{ route('admin.products.unhide', $product) }}">
                                        @csrf

                                        <button class="w-full px-4 py-3 rounded-xl bg-green-600 text-white font-semibold">
                                            Rendre visible
                                        </button>
                                    </form>
                                @endif

                            @endif

                            @if ($product->status !== 'banned')
                                <button type="button" @click="modal = 'ban-product'"
                                    class="w-full px-4 py-3 rounded-xl bg-red-600 text-white font-semibold">
                                    Bannir le produit
                                </button>
                            @else
                                <form method="POST" action="{{ route('admin.products.unban', $product) }}">
                                    @csrf

                                    <button class="w-full px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold">
                                        Réhabiliter le produit
                                    </button>
                                </form>
                            @endif

                            @if ($product->trashed())
                                <form method="POST" action="{{ route('admin.products.restore', $product) }}">
                                    @csrf

                                    <button
                                        class="w-full px-4 py-3 rounded-xl border border-green-600 text-green-700 font-semibold">
                                        Restaurer le produit
                                    </button>
                                </form>
                            @else
                                <button type="button" @click="modal = 'delete-product'"
                                    class="w-full px-4 py-3 rounded-xl border border-red-300 text-red-600 font-semibold">
                                    Supprimer le produit
                                </button>
                            @endif

                        </div>

                    </div>

                    {{-- Historique --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">

                        <h2 class="font-bold text-lg text-gray-900 mb-5">
                            Historique administratif
                        </h2>

                        <div class="space-y-4 max-h-[500px] overflow-y-auto">

                            @forelse($logs as $log)
                                <div class="border-l-2 border-[#016837] pl-4">

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $log->action }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $log->created_at?->format('d/m/Y H:i') }}
                                    </p>

                                    @if ($log->note)
                                        <p class="text-sm text-gray-600 mt-2">
                                            {{ $log->note }}
                                        </p>
                                    @endif

                                </div>

                            @empty

                                <p class="text-sm text-gray-500">
                                    Aucune action administrative enregistrée.
                                </p>
                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- =========================================================
     MODAL : BANNIR PRODUIT
========================================================== --}}
        <div x-cloak x-show="modal === 'ban-product'"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">

            <div @click.outside="modal = null" class="bg-white rounded-2xl w-full max-w-lg p-6">

                <h2 class="text-xl font-bold">
                    Bannir le produit
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Cette action doit être justifiée.
                </p>

                <form method="POST" action="{{ route('admin.products.ban', $product) }}" class="mt-5">

                    @csrf

                    <textarea name="reason" required minlength="5" rows="4" placeholder="Motif du bannissement..."
                        class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>

                    <div class="flex justify-end gap-3 mt-4">

                        <button type="button" @click="modal = null" class="px-4 py-2 rounded-lg border border-gray-300">
                            Annuler
                        </button>

                        <button class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold">
                            Confirmer
                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- MODAL SUPPRESSION --}}
        <div x-cloak x-show="modal === 'delete-product'"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">

            <div @click.outside="modal = null" class="bg-white rounded-2xl w-full max-w-lg p-6">

                <h2 class="text-xl font-bold text-red-600">
                    Supprimer le produit
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Le produit sera supprimé logiquement.
                </p>

                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="mt-5">

                    @csrf
                    @method('DELETE')

                    <textarea name="reason" required minlength="5" rows="4" placeholder="Motif de la suppression..."
                        class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>

                    <div class="flex justify-end gap-3 mt-4">

                        <button type="button" @click="modal = null" class="px-4 py-2 rounded-lg border border-gray-300">
                            Annuler
                        </button>

                        <button class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold">
                            Supprimer
                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- MODAL SUSPENSION BOUTIQUE --}}
        <div x-cloak x-show="modal === 'suspend-shop'"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">

            <div class="bg-white rounded-2xl w-full max-w-lg p-6">

                <h2 class="text-xl font-bold">
                    Suspendre la boutique
                </h2>

                <form method="POST" action="{{ route('admin.products.shop.suspend', $product->shop) }}" class="mt-5">

                    @csrf

                    <textarea name="reason" required minlength="5" rows="4" placeholder="Motif..."
                        class="w-full rounded-xl border-gray-300"></textarea>

                    <div class="flex justify-end gap-3 mt-4">

                        <button type="button" @click="modal = null" class="px-4 py-2 rounded-lg border">
                            Annuler
                        </button>

                        <button class="px-4 py-2 rounded-lg bg-yellow-500 text-white font-semibold">
                            Suspendre
                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- MODAL BAN BOUTIQUE --}}
        <div x-cloak x-show="modal === 'ban-shop'"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">

            <div class="bg-white rounded-2xl w-full max-w-lg p-6">

                <h2 class="text-xl font-bold text-red-600">
                    Bannir la boutique
                </h2>

                <form method="POST" action="{{ route('admin.products.shop.ban', $product->shop) }}" class="mt-5">

                    @csrf

                    <textarea name="reason" required minlength="5" rows="4" placeholder="Motif..."
                        class="w-full rounded-xl border-gray-300"></textarea>

                    <div class="flex justify-end gap-3 mt-4">

                        <button type="button" @click="modal = null" class="px-4 py-2 rounded-lg border">
                            Annuler
                        </button>

                        <button class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold">
                            Bannir
                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- MODAL SUSPENSION VENDEUR --}}
        <div x-cloak x-show="modal === 'suspend-seller'"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">

            <div class="bg-white rounded-2xl w-full max-w-lg p-6">

                <h2 class="text-xl font-bold">
                    Suspendre le vendeur
                </h2>

                <form method="POST" action="{{ route('admin.products.seller.suspend', $product->shop->user) }}"
                    class="mt-5">

                    @csrf

                    <textarea name="reason" required minlength="5" rows="4" placeholder="Motif..."
                        class="w-full rounded-xl border-gray-300"></textarea>

                    <div class="flex justify-end gap-3 mt-4">

                        <button type="button" @click="modal = null" class="px-4 py-2 rounded-lg border">
                            Annuler
                        </button>

                        <button class="px-4 py-2 rounded-lg bg-yellow-500 text-white font-semibold">
                            Suspendre
                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- MODAL BAN VENDEUR --}}
        <div x-cloak x-show="modal === 'ban-seller'"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">

            <div class="bg-white rounded-2xl w-full max-w-lg p-6">

                <h2 class="text-xl font-bold text-red-600">
                    Bannir le vendeur
                </h2>

                <form method="POST" action="{{ route('admin.products.seller.ban', $product->shop->user) }}"
                    class="mt-5">

                    @csrf

                    <textarea name="reason" required minlength="5" rows="4" placeholder="Motif..."
                        class="w-full rounded-xl border-gray-300"></textarea>

                    <div class="flex justify-end gap-3 mt-4">

                        <button type="button" @click="modal = null" class="px-4 py-2 rounded-lg border">
                            Annuler
                        </button>

                        <button class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold">
                            Bannir
                        </button>

                    </div>

                </form>

            </div>

        </div>


    </div>

@endsection
