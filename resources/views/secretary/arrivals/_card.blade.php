<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <div class="flex items-start justify-between mb-3">
        <div>
            <p class="font-bold text-gray-900">{{ $order->reference }}</p>
            <p class="text-xs text-gray-400 mt-0.5">
                Expédié le {{ $order->shipped_at?->format('d/m/Y') ?? 'N/A' }}
            </p>
        </div>
        <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2.5 py-1 rounded-full">
            {{ $order->status === 'in_transit' ? 'En transit' : 'Enregistré' }}
        </span>
    </div>

    <div class="space-y-1.5 text-sm mb-4">
        <div class="flex justify-between">
            <span class="text-gray-500">Vendeur</span>
            <span class="font-medium">{{ $order->shop->name }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Destinataire</span>
            <span class="font-medium">{{ $order->shipment->recipient_name }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Transport</span>
            @if ($order->shipment->shipping_included)
                <span class="text-emerald-600 font-medium">Inclus</span>
            @else
                <span class="text-orange-500 font-medium">
                    {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                    — payé par acheteur à la remise
                </span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('secretary.arrival.validate', $order) }}">
        @csrf
        <button
            class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold
                       py-3 rounded-xl transition text-sm">
            ✓ Valider l'arrivée
        </button>
    </form>
</div>
