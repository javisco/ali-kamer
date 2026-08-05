@extends('base')

@section('title', 'Commande ' . $order->reference)

@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">{{ $order->reference }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Reçue le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <a href="{{ route('seller.orders.index') }}" class="text-sm text-indigo-600 hover:underline font-medium">
                    ← Retour
                </a>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Action principale selon le statut --}}
            @if ($order->status === 'paid')
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-6">
                    <h2 class="font-bold text-blue-800 mb-2">✅ Paiement confirmé — À vous de jouer !</h2>
                    <p class="text-sm text-blue-700 mb-4">
                        Préparez le colis et déposez-le à l'agence de voyage.
                        Donnez le code ci-dessous au secrétaire lors du dépôt.
                    </p>

                    {{-- Code de dépôt --}}
                    <div class="bg-white rounded-xl border border-blue-200 p-4 text-center mb-4">
                        <p class="text-xs text-gray-500 mb-1">Code de dépôt à donner au secrétaire</p>
                        <p class="text-3xl font-extrabold tracking-[0.2em] text-blue-700">
                            {{ $order->deposit_code }}
                        </p>
                    </div>

                    {{-- Infos colis à inscrire --}}
                    <div class="bg-white rounded-xl border border-blue-100 p-4 text-sm space-y-1">
                        <p class="font-semibold text-gray-700 mb-2">Informations à inscrire sur le colis :</p>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Destinataire</span>
                            <span class="font-medium">{{ $order->shipment->recipient_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Téléphone</span>
                            <span class="font-medium">{{ $order->shipment->recipient_phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ville destination</span>
                            <span class="font-medium">{{ $order->shipment->destination_city }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('seller.orders.preparing', $order) }}" class="mt-4">
                        @csrf
                        <button
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold
                               py-3 rounded-xl transition text-sm">
                            Marquer comme "En préparation"
                        </button>
                    </form>
                </div>
            @endif

            @if ($order->status === 'preparing')
                <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5 mb-6">
                    <h2 class="font-bold text-indigo-800 mb-2">📦 En préparation</h2>
                    <p class="text-sm text-indigo-700 mb-3">
                        Déposez le colis à l'agence de voyage et donnez le code au secrétaire.
                    </p>
                    <div class="bg-white rounded-xl border border-indigo-200 p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">Code de dépôt</p>
                        <p class="text-3xl font-extrabold tracking-[0.2em] text-indigo-700">
                            {{ $order->deposit_code }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-5">

                {{-- Acheteur --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-3">Acheteur</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nom</span>
                            <span class="font-medium">{{ $order->buyer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Téléphone</span>
                            <span class="font-medium">{{ $order->buyer->phone }}</span>
                        </div>
                        @if ($order->buyer_note)
                            <div class="border-t border-gray-50 pt-2 mt-2">
                                <p class="text-gray-500 mb-1">Note de l'acheteur</p>
                                <p class="text-gray-800 italic text-xs bg-gray-50 rounded-lg p-2">
                                    "{{ $order->buyer_note }}"
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Articles --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4">Articles</h2>
                    @foreach ($order->items as $item)
                        <div
                            class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->product_title }}</p>
                                <p class="text-xs text-gray-400">{{ $item->quantity }} ×
                                    {{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <p class="font-bold text-sm">{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</p>
                        </div>
                    @endforeach
                </div>

                {{-- Gains vendeur --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="font-bold text-gray-800 mb-4">Vos gains</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Sous-total</span>
                            <span>{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <span>Commission plateforme (5%)</span>
                            <span>- {{ number_format($order->platform_commission, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <span>Commission agences (1%)</span>
                            <span>- {{ number_format($order->agency_commission, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <span>Frais retrait MoMo (1%)</span>
                            <span>- {{ number_format($order->gateway_payout_fee, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-900 border-t border-gray-100 pt-2 mt-2">
                            <span>Montant net</span>
                            <span class="text-emerald-600 text-lg">
                                {{ number_format($order->net_amount, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">
                        Ce montant sera crédité sur votre portefeuille après confirmation de livraison.
                    </p>
                </div>
                @if ($order->status === 'disputed')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                        <p class="text-sm font-bold text-red-700">⚠ Litige ouvert sur cette commande</p>
                        <p class="text-xs text-red-600 mt-1">
                            Un administrateur examine le dossier.
                            Allez dans <a href="{{ route('seller.disputes.index') }}" class="underline">Mes litiges</a>
                            pour répondre.
                        </p>
                    </div>
                @endif
                @if ($order->isCompleted())
                    <form method="POST" action="{{ route('seller.reviews.store', $order) }}" class="space-y-3">
                        @csrf
                        <h3 class="font-bold text-gray-800 text-sm">Noter l'acheteur</h3>
                        <div class="flex gap-2" id="buyerStars">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setBuyerRating({{ $i }})"
                                    class="text-2xl text-gray-300 hover:text-yellow-400 star-buyer"
                                    data-value="{{ $i }}">★</button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="buyer_rating" required>
                        <textarea name="body" rows="2" placeholder="Commentaire optionnel..."
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm"></textarea>
                        <button
                            class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold
                       py-2.5 rounded-xl transition text-sm">
                            Noter l'acheteur
                        </button>
                    </form>

                    @push('scripts')
                        <script>
                            function setBuyerRating(value) {
                                document.getElementById('buyer_rating').value = value;
                                document.querySelectorAll('.star-buyer').forEach(star => {
                                    star.classList.toggle('text-yellow-400', parseInt(star.dataset.value) <= value);
                                    star.classList.toggle('text-gray-300', parseInt(star.dataset.value) > value);
                                });
                            }
                        </script>
                    @endpush
                @endif
                {{-- Livraison --}}
                @if ($order->shipment)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="font-bold text-gray-800 mb-3">Livraison</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Type</span>
                                <span class="font-medium capitalize">{{ $order->shipment->type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Destination</span>
                                <span class="font-medium">{{ $order->shipment->destination_city }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Transport</span>
                                <span
                                    class="{{ $order->shipment->shipping_included ? 'text-emerald-600' : 'text-orange-500' }} font-medium">
                                    {{ $order->shipment->shipping_included ? 'Inclus' : 'Payé par acheteur à l\'arrivée' }}
                                </span>
                            </div>
                            @if ($order->shipment->registered_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Déposé le</span>
                                    <span>{{ $order->shipment->registered_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if ($order->shipment->arrived_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Arrivé le</span>
                                    <span>{{ $order->shipment->arrived_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
