@extends('base')

@section('title', 'Commandes reçues')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-5xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Commandes reçues</h1>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-500">Aucune commande reçue pour l'instant.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-5 py-3">Référence</th>
                        <th class="text-left px-5 py-3">Acheteur</th>
                        <th class="text-left px-5 py-3">Montant</th>
                        <th class="text-left px-5 py-3">Statut</th>
                        <th class="text-left px-5 py-3">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($orders as $order)
                        @php
                            $statusConfig = [
                                'paid'                         => ['label' => 'Payé — À préparer',  'class' => 'bg-blue-100 text-blue-700'],
                                'preparing'                    => ['label' => 'En préparation',      'class' => 'bg-indigo-100 text-indigo-700'],
                                'registered_origin'            => ['label' => 'Déposé',              'class' => 'bg-indigo-100 text-indigo-700'],
                                'in_transit'                   => ['label' => 'En transit',           'class' => 'bg-purple-100 text-purple-700'],
                                'arrived_destination'          => ['label' => 'Arrivé',               'class' => 'bg-teal-100 text-teal-700'],
                                'awaiting_buyer_confirmation'  => ['label' => 'En attente acheteur', 'class' => 'bg-orange-100 text-orange-700'],
                                'completed'                    => ['label' => 'Terminée ✓',           'class' => 'bg-emerald-100 text-emerald-700'],
                                'auto_completed'               => ['label' => 'Terminée ✓',           'class' => 'bg-emerald-100 text-emerald-700'],
                                'disputed'                     => ['label' => 'Litige ⚠',            'class' => 'bg-red-100 text-red-700'],
                                'cancelled'                    => ['label' => 'Annulée',              'class' => 'bg-gray-100 text-gray-500'],
                            ];
                            $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-900 text-sm">{{ $order->reference }}</p>
                                <p class="text-xs text-gray-400">{{ $order->items->count() }} article(s)</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-gray-800">{{ $order->buyer->name }}</p>
                                <p class="text-xs text-gray-400">{{ $order->buyer->phone }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-gray-900 text-sm">
                                    {{ number_format($order->net_amount, 0, ',', ' ') }} FCFA
                                </p>
                                <p class="text-xs text-gray-400">net vendeur</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">
                                    {{ $sc['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-400">
                                {{ $order->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('seller.orders.show', $order) }}"
                                   class="text-indigo-600 hover:underline text-sm font-medium">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $orders->links() }}</div>
    @endif

</div>
</div>
@endsection
