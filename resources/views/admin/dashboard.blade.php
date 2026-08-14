@extends('base')
@section('title', 'Dashboard Admin')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Dashboard Admin</h1>

            {{-- KPIs --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Commandes aujourd'hui</p>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $kpis['orders_today'] }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">CA aujourd'hui</p>
                    <p class="text-2xl font-extrabold text-indigo-600">
                        {{ number_format($kpis['revenue_today'], 0, ',', ' ') }}
                        <span class="text-sm font-semibold">FCFA</span>
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Litiges ouverts</p>
                    <p class="text-3xl font-extrabold {{ $kpis['disputes_open'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $kpis['disputes_open'] }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">KYC en attente</p>
                    <p class="text-3xl font-extrabold {{ $kpis['kyc_pending'] > 0 ? 'text-orange-600' : 'text-gray-900' }}">
                        {{ $kpis['kyc_pending'] }}
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total séquestré</p>
                    <p class="text-xl font-extrabold text-gray-700">
                        {{ number_format($kpis['total_escrow'], 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total disponible vendeurs</p>
                    <p class="text-xl font-extrabold text-emerald-600">
                        {{ number_format($kpis['total_available'], 0, ',', ' ') }} FCFA
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Nouveaux vendeurs</p>
                    <p class="text-3xl font-extrabold text-gray-900">{{ $kpis['sellers_today'] }}</p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                {{-- KYC en attente --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="font-bold text-gray-800">KYC à valider</h2>
                        <a href="{{ route('admin.kyc.index') }}" class="text-sm text-indigo-600 hover:underline">Voir tout
                            →</a>
                    </div>
                    @forelse($pendingKyc as $kyc)
                        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $kyc->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $kyc->created_at->diffForHumans() }}</p>
                            </div>
                            <a href="{{ route('admin.kyc.show', $kyc) }}"
                                class="text-sm text-indigo-600 hover:underline font-medium">
                                Examiner →
                            </a>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-gray-400 text-sm text-center">Aucun dossier en attente.</p>
                    @endforelse
                </div>



                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="font-bold text-gray-800">Listes des Utilisateurs</h2>
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:underline">Voir
                            tout
                            →</a>
                    </div>

                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="font-bold text-gray-800">listes des agences</h2>
                        <a href="{{ route('admin.agencies.index') }}" class="text-sm text-indigo-600 hover:underline">Voir
                            tout
                            →</a>
                    </div>

                </div>

                {{-- Litiges urgents --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="font-bold text-gray-800">Litiges urgents</h2>
                        <a href="{{ route('admin.disputes.index') }}" class="text-sm text-indigo-600 hover:underline">Voir
                            tout →</a>
                    </div>
                    @forelse($openDisputes as $dispute)
                        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $dispute->order->reference }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $dispute->typeLabel() }}
                                    — {{ $dispute->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('admin.disputes.show', $dispute) }}"
                                class="text-sm text-red-600 hover:underline font-medium">
                                Traiter →
                            </a>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-gray-400 text-sm text-center">Aucun litige urgent.</p>
                    @endforelse
                </div>

            </div>

            {{-- Commandes récentes --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50">
                    <h2 class="font-bold text-gray-800">Commandes récentes</h2>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="text-left px-5 py-3">Référence</th>
                            <th class="text-left px-5 py-3">Acheteur</th>
                            <th class="text-left px-5 py-3">Boutique</th>
                            <th class="text-left px-5 py-3">Montant</th>
                            <th class="text-left px-5 py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-sm font-medium">{{ $order->reference }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $order->buyer->name }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $order->shop->name }}</td>
                                <td class="px-5 py-3 text-sm font-bold">
                                    {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
