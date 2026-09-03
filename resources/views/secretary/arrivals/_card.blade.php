<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5
            hover:shadow-md hover:border-green-100 transition-all duration-200">

    <div class="flex items-start justify-between mb-3">
        <div>
            <p class="font-bold text-gray-900">{{ $order->reference }}</p>
            <p class="text-xs text-gray-400 mt-0.5">
                Expédié le {{ $order->shipped_at?->format('d/m/Y') ?? 'N/A' }}
            </p>
        </div>

        <span class="bg-green-50 text-[#016837] border border-green-100
                     text-xs font-semibold px-2.5 py-1 rounded-full">
            {{ $order->status === 'in_transit' ? 'En transit' : 'Enregistré' }}
        </span>
    </div>

    <div class="space-y-1.5 text-sm mb-4">
        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Vendeur</span>
            <span class="font-medium text-gray-900 text-right">
                {{ $order->shop->name }}
            </span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Destinataire</span>
            <span class="font-medium text-gray-900 text-right">
                {{ $order->shipment->recipient_name }}
            </span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Transport</span>

            @if ($order->shipment->shipping_included)
                <span class="text-[#016837] font-semibold">
                    Inclus
                </span>
            @else
                <span class="text-[#E30613] font-medium text-right">
                    {{ number_format($order->shipment->transport_fee, 0, ',', ' ') }} FCFA
                    <span class="text-xs text-gray-500">
                        — payé par acheteur à la remise
                    </span>
                </span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('secretary.arrival.validate', $order) }}">
        @csrf

        <button
            class="w-full bg-[#016837] hover:bg-[#0a542d] active:bg-[#064323]
                   text-white font-bold py-3 rounded-xl transition-all
                   text-sm shadow-sm hover:shadow-md">
            ✓ Valider l'arrivée
        </button>
    </form>
</div>