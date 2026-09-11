@extends('layouts.buyer')
@section('title', 'Mon achat')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
<div class="max-w-3xl mx-auto px-4 sm:px-6">

    <div class="mb-6">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Achat groupé</p>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $group->reference }}</h1>
        <p class="text-sm text-slate-500 mt-1">
            Payé le {{ $group->paid_at?->format('d/m/Y à H:i') ?? '—' }} ·
            <span class="font-bold text-[#016837]">{{ number_format($group->total_amount, 0, ',', ' ') }} FCFA</span>
            pour {{ $group->orders->count() }} commande(s)
        </p>
    </div>

    <div class="space-y-4">
        @php
            $statusConfig = [
                'pending'                     => ['label' => 'En attente',        'class' => 'bg-slate-100 text-slate-600 border-slate-200'],
                'awaiting_payment'            => ['label' => 'Paiement requis',   'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                'paid'                        => ['label' => 'Payé',              'class' => 'bg-emerald-50 text-[#016837] border-emerald-200'],
                'preparing'                   => ['label' => 'En préparation',    'class' => 'bg-emerald-50 text-[#016837] border-emerald-200'],
                'registered_origin'           => ['label' => 'Déposé en agence', 'class' => 'bg-emerald-50 text-[#016837] border-emerald-200'],
                'in_transit'                  => ['label' => 'En transit',        'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                'arrived_destination'         => ['label' => 'Arrivé',            'class' => 'bg-teal-50 text-teal-700 border-teal-200'],
                'awaiting_buyer_confirmation' => ['label' => 'À retirer',         'class' => 'bg-amber-50 text-[#F9A01B] border-amber-200'],
                'completed'                   => ['label' => 'Livré ✓',           'class' => 'bg-emerald-100 text-[#016837] border-emerald-300'],
                'auto_completed'              => ['label' => 'Livré ✓',           'class' => 'bg-emerald-100 text-[#016837] border-emerald-300'],
                'disputed'                    => ['label' => 'Litige',            'class' => 'bg-red-50 text-[#E30613] border-red-200'],
                'cancelled'                   => ['label' => 'Annulée',           'class' => 'bg-slate-100 text-slate-500 border-slate-200'],
                'failed'                      => ['label' => 'Échouée',           'class' => 'bg-red-50 text-[#E30613] border-red-200'],
            ];
        @endphp

        @foreach($group->orders as $order)
            @php $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200']; @endphp

            <a href="{{ route('buyer.orders.show', $order) }}"
               class="block bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-[#016837]/50 transition-all p-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="font-black text-slate-900 text-sm">{{ $order->reference }}</p>
                    <span class="px-3 py-1 rounded-xl text-[11px] font-extrabold border uppercase tracking-wider {{ $sc['class'] }}">
                        {{ $sc['label'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-800">{{ $order->shop->name }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $order->items->count() }} article(s)</p>
                    </div>
                    <p class="font-black text-[#016837] text-base">
                        {{ number_format($order->total_amount, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                    </p>
                </div>
            </a>
        @endforeach
    </div>

</div>
</div>
@endsection