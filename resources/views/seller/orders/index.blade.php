@extends('layouts.seller')

@section('title', 'Commandes reçues - ALI-KAMER')

@section('content')
<div class="p-4 lg:p-8 space-y-6">

    {{-- En-tête de la page --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Commandes reçues</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">Gérez le traitement, la préparation et le suivi de vos ventes.</p>
        </div>
    </div>

    {{-- Alerte de succès --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($orders->isEmpty())
        {{-- État vide --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-800">Aucune commande pour le moment</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Dès que vos clients effectueront des achats, leurs commandes s'afficheront ici.</p>
        </div>
    @else
        {{-- LISTE DES COMMANDES --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            
            {{-- Vue Tableau (Écrans moyens et grands) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Référence</th>
                            <th class="px-6 py-4">Acheteur</th>
                            <th class="px-6 py-4">Montant Net</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @foreach($orders as $order)
                            @php
                                $statusConfig = [
                                    'paid'                         => ['label' => 'À préparer',          'class' => 'bg-blue-50 text-blue-700 border-blue-200',     'dot' => 'bg-blue-500'],
                                    'preparing'                    => ['label' => 'En préparation',      'class' => 'bg-amber-50 text-amber-700 border-amber-200',   'dot' => 'bg-amber-500'],
                                    'registered_origin'            => ['label' => 'Déposé',              'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'dot' => 'bg-indigo-500'],
                                    'in_transit'                   => ['label' => 'En transit',           'class' => 'bg-purple-50 text-purple-700 border-purple-200', 'dot' => 'bg-purple-500'],
                                    'arrived_destination'          => ['label' => 'Arrivé',               'class' => 'bg-teal-50 text-teal-700 border-teal-200',     'dot' => 'bg-teal-500'],
                                    'awaiting_buyer_confirmation'  => ['label' => 'Attente confirmation', 'class' => 'bg-orange-50 text-orange-700 border-orange-200', 'dot' => 'bg-orange-500'],
                                    'completed'                    => ['label' => 'Terminée',            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                                    'auto_completed'               => ['label' => 'Terminée (Auto)',     'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                                    'disputed'                     => ['label' => 'Litige ouvert',       'class' => 'bg-rose-50 text-rose-700 border-rose-200',       'dot' => 'bg-rose-500'],
                                    'cancelled'                    => ['label' => 'Annulée',              'class' => 'bg-slate-100 text-slate-500 border-slate-200',  'dot' => 'bg-slate-400'],
                                ];
                                $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">#{{ $order->reference }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $order->items->count() }} article(s)</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">{{ $order->buyer->name ?? 'Acheteur' }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $order->buyer->phone ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-black text-slate-900">{{ number_format($order->net_amount, 0, ',', ' ') }} <span class="text-[10px] font-bold text-slate-500">FCFA</span></p>
                                    <p class="text-[10px] text-emerald-600 font-semibold">Net vendeur</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sc['class'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                        {{ $sc['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium text-[11px]">
                                    {{ $order->created_at->format('d/m/Y') }}
                                    <span class="block text-[10px] text-slate-400">{{ $order->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('seller.orders.show', $order) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-[#1769E0] font-bold text-xs hover:bg-[#1769E0] hover:text-white transition-all">
                                        <span>Détails</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Vue Cartes Mobile (< md) --}}
            <div class="md:hidden divide-y divide-slate-100">
                @foreach($orders as $order)
                    @php
                        $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'];
                    @endphp
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-900">#{{ $order->reference }}</span>
                                <span class="text-[11px] text-slate-400 ml-2">({{ $order->items->count() }} art.)</span>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $sc['class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                {{ $sc['label'] }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl">
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $order->buyer->name ?? 'Acheteur' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $order->buyer->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-900">{{ number_format($order->net_amount, 0, ',', ' ') }} FCFA</p>
                                <p class="text-[9px] text-emerald-600 font-bold">Net vendeur</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <span class="text-[10px] font-medium text-slate-400">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
                            <a href="{{ route('seller.orders.show', $order) }}" class="text-xs font-bold text-[#1769E0] hover:underline flex items-center gap-1">
                                Voir la commande →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        {{-- Pagination --}}
        <div class="pt-2">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection