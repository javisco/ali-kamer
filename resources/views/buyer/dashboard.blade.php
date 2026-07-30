@extends('base')

@section('title', 'Tableau de bord - Acheteur')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- En-tête de bienvenue -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                Bonjour, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-sm text-gray-600 mt-1">
                Suivez vos achats sécurisés, vos commandes en transit et votre historique en un coup d'œil.
            </p>
        </div>

        <!-- Cartes de Statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Commandes en cours</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['active_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Livrées & Terminées</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['completed_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Litiges / Réclamations</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['disputed_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dépensé</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_spent'], 0, ',', ' ') }}
                        FCFA</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

        </div>

        <!-- Section Commande Active Prioritaire (Focus Séquestre & OTP) -->
        @if ($activeOrder)
            <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-200 border border-blue-400/30 mb-3">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping mr-2"></span>
                            Commande en cours d'expédition
                        </div>
                        <h2 class="text-xl font-bold">Réf : {{ $activeOrder->reference }}</h2>
                        <p class="text-blue-200 text-sm mt-1">
                            Vendeur : <span class="font-semibold text-white">{{ $activeOrder->shop->name }}</span> |
                            Destination : <span
                                class="font-semibold text-white">{{ $activeOrder->shipment->destination_city }}</span>
                        </p>
                    </div>

                    <!-- OTP Box si disponible -->
                    @if (
                        $activeOrder->status === \App\Models\Order::STATUS_ARRIVED_DESTINATION ||
                            $activeOrder->status === \App\Models\Order::STATUS_AWAITING_BUYER_CONFIRMATION)
                        <div
                            class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center min-w-[220px]">
                            <p class="text-xs text-blue-200 uppercase tracking-wider">Votre Code OTP à remettre</p>
                            <p class="text-3xl font-extrabold tracking-widest text-amber-400 my-1">
                                {{ $activeOrder->otp_code }}</p>
                            <p class="text-xs text-blue-200">À donner au guichet pour retirer le colis</p>
                        </div>
                    @else
                        <a href="{{ route('buyer.orders.show', $activeOrder) }}"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white text-blue-900 font-semibold hover:bg-blue-50 transition shadow-sm">
                            Suivre le colis
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Tableau des Dernières Commandes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Commandes Récents</h2>
                <a href="{{ route('buyer.orders.index') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                    Voir tout →
                </a>
            </div>

            @if ($recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold">
                            <tr>
                                <th class="px-6 py-3">Référence</th>
                                <th class="px-6 py-3">Boutique</th>
                                <th class="px-6 py-3">Montant Total</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $order->reference }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $order->shop->name }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-gray-100 text-gray-700',
                                                'awaiting_payment' => 'bg-amber-100 text-amber-800',
                                                'paid' => 'bg-blue-100 text-blue-800',
                                                'preparing' => 'bg-indigo-100 text-indigo-800',
                                                'registered_origin' => 'bg-purple-100 text-purple-800',
                                                'in_transit' => 'bg-blue-100 text-blue-800',
                                                'arrived_destination' => 'bg-emerald-100 text-emerald-800',
                                                'awaiting_buyer_confirmation' => 'bg-amber-100 text-amber-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'auto_completed' => 'bg-green-100 text-green-800',
                                                'disputed' => 'bg-red-100 text-red-800',
                                                'cancelled' => 'bg-gray-100 text-gray-500',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $order->created_at->format('d/m/Y à H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('buyer.orders.show', $order) }}"
                                            class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <p class="mb-4">Vous n'avez pas encore passé de commande.</p>
                    <a href="{{ route('buyer.home') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                        Explorer le catalogue
                    </a>
                </div>
            @endif
        </div>

    </div>
@endsection
