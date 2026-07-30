@extends('base')

@section('title', 'Mes commandes')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Mes commandes</h1>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Aucune commande</h2>
            <p class="text-gray-500 text-sm mb-4">Vous n'avez pas encore passé de commande.</p>
            <a href="{{ route('buyer.home') }}"
               class="inline-block bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition text-sm">
                Explorer le catalogue
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $statusConfig = [
                        'pending'                     => ['label' => 'En attente',        'class' => 'bg-gray-100 text-gray-600'],
                        'awaiting_payment'             => ['label' => 'Paiement requis',   'class' => 'bg-yellow-100 text-yellow-700'],
                        'paid'                         => ['label' => 'Payé',              'class' => 'bg-blue-100 text-blue-700'],
                        'preparing'                    => ['label' => 'En préparation',    'class' => 'bg-indigo-100 text-indigo-700'],
                        'registered_origin'            => ['label' => 'Déposé en agence', 'class' => 'bg-indigo-100 text-indigo-700'],
                        'in_transit'                   => ['label' => 'En transit',        'class' => 'bg-purple-100 text-purple-700'],
                        'arrived_destination'          => ['label' => 'Arrivé',            'class' => 'bg-teal-100 text-teal-700'],
                        'awaiting_buyer_confirmation'  => ['label' => 'À retirer',         'class' => 'bg-orange-100 text-orange-700'],
                        'completed'                    => ['label' => 'Livré ✓',           'class' => 'bg-emerald-100 text-emerald-700'],
                        'auto_completed'               => ['label' => 'Livré ✓',           'class' => 'bg-emerald-100 text-emerald-700'],
                        'disputed'                     => ['label' => 'Litige',            'class' => 'bg-red-100 text-red-700'],
                        'cancelled'                    => ['label' => 'Annulée',           'class' => 'bg-gray-100 text-gray-500'],
                        'failed'                       => ['label' => 'Échouée',           'class' => 'bg-red-100 text-red-600'],
                    ];
                    $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600'];
                @endphp

                <a href="{{ route('buyer.orders.show', $order) }}"
                   class="block bg-white rounded-2xl border border-gray-100 shadow-sm
                          hover:shadow-md hover:border-indigo-200 transition-all p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $order->reference }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">
                            {{ $sc['label'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-700">{{ $order->shop->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $order->items->count() }} article(s)
                            </p>
                        </div>
                        <p class="font-extrabold text-indigo-600">
                            {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                        </p>
                    </div>

                    @if($order->status === 'awaiting_buyer_confirmation')
                        <div class="mt-3 bg-orange-50 rounded-xl px-3 py-2 text-xs text-orange-700 font-semibold">
                            📦 Votre colis est disponible — Venez le récupérer avec votre OTP
                        </div>
                    @endif

                    @if($order->status === 'awaiting_payment')
                        <div class="mt-3 bg-yellow-50 rounded-xl px-3 py-2 text-xs text-yellow-700 font-semibold">
                            💰 En attente de validation de votre paiement
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
    @endif

</div>
</div>
@endsection
