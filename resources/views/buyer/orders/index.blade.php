@extends('layouts.buyer')
@section('title', 'Mes commandes')

@section('content')
<div class="bg-slate-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4 sm:px-6">

    {{-- En-tête de la page --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-[#016837]"></div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight">Mes commandes</h1>
        </div>
        <span class="text-xs font-bold text-slate-500 bg-slate-200/60 px-3 py-1 rounded-xl">
            {{ $orders->total() ?? $orders->count() }} commande(s)
        </span>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-[#016837] px-5 py-3.5 rounded-2xl mb-6 text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-[#016837] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="text-base font-bold text-slate-800 mb-1">Aucune commande effectuée</h2>
            <p class="text-slate-500 text-xs mb-6">Vous n'avez pas encore passé de commande sur Ali-Kamer.</p>
            <a href="{{ route('buyer.home') }}"
               class="inline-flex items-center gap-2 bg-[#016837] hover:bg-[#01522b] text-white font-extrabold px-6 py-3 rounded-xl transition text-xs shadow-md shadow-[#016837]/20">
                <span>Explorer le catalogue</span>
                <svg class="w-4 h-4 text-[#F9A01B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $statusConfig = [
                        'pending'                     => ['label' => 'En attente',        'class' => 'bg-slate-100 text-slate-600 border-slate-200'],
                        'awaiting_payment'             => ['label' => 'Paiement requis',   'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        'paid'                         => ['label' => 'Payé',              'class' => 'bg-emerald-50 text-[#016837] border-emerald-200'],
                        'preparing'                    => ['label' => 'En préparation',    'class' => 'bg-emerald-50 text-[#016837] border-emerald-200'],
                        'registered_origin'            => ['label' => 'Déposé en agence', 'class' => 'bg-emerald-50 text-[#016837] border-emerald-200'],
                        'in_transit'                   => ['label' => 'En transit',        'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                        'arrived_destination'          => ['label' => 'Arrivé',            'class' => 'bg-teal-50 text-teal-700 border-teal-200'],
                        'awaiting_buyer_confirmation'  => ['label' => 'À retirer',         'class' => 'bg-amber-50 text-[#F9A01B] border-amber-200'],
                        'completed'                    => ['label' => 'Livré ✓',           'class' => 'bg-emerald-100 text-[#016837] border-emerald-300'],
                        'auto_completed'               => ['label' => 'Livré ✓',           'class' => 'bg-emerald-100 text-[#016837] border-emerald-300'],
                        'disputed'                     => ['label' => 'Litige',            'class' => 'bg-red-50 text-[#E30613] border-red-200'],
                        'cancelled'                    => ['label' => 'Annulée',           'class' => 'bg-slate-100 text-slate-500 border-slate-200'],
                        'failed'                       => ['label' => 'Échouée',           'class' => 'bg-red-50 text-[#E30613] border-red-200'],
                    ];
                    $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200'];
                @endphp

                <a href="{{ route('buyer.orders.show', $order) }}"
                   class="block bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md hover:border-[#016837]/50 transition-all p-5 group">
                    
                    {{-- Ligne supérieure : Réf & Statut --}}
                    <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-3">
                        <div>
                            <p class="font-black text-slate-900 text-sm tracking-tight group-hover:text-[#016837] transition-colors">
                                {{ $order->reference }}
                            </p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                                Passer le {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-xl text-[11px] font-extrabold border uppercase tracking-wider {{ $sc['class'] }}">
                            {{ $sc['label'] }}
                        </span>
                    </div>

                    {{-- Ligne inférieure : Boutique & Total --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $order->shop->name }}</p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                {{ $order->items->count() }} article(s)
                            </p>
                        </div>
                        <p class="font-black text-[#016837] text-base sm:text-lg">
                            {{ number_format($order->total_amount, 0, ',', ' ') }} <span class="text-xs font-bold">FCFA</span>
                        </p>
                    </div>

                    {{-- En encadré d'alerte selon l'état --}}
                    @if($order->status === 'awaiting_buyer_confirmation')
                        <div class="mt-3 bg-[#F9A01B]/10 border border-[#F9A01B]/30 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-bold flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span>📦</span> Votre colis est disponible en agence
                            </span>
                            <span class="text-[#016837] font-black underline">Récupérer avec OTP →</span>
                        </div>
                    @endif

                    @if($order->status === 'awaiting_payment')
                        <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl px-3.5 py-2 text-xs text-amber-800 font-bold flex items-center gap-2">
                            <span>💰</span> En attente de validation de votre paiement mobile
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">{{ $orders->links() }}</div>
    @endif

</div>
</div>
@endsection