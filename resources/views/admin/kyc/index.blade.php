@extends('layouts.admin')
@section('title', 'Gestion KYC Vendeurs')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Titre et En-tête --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#006837]">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#006837]"></span>
                    Conformité & Identité
                </div>
                <h1 class="mt-1 text-2xl sm:text-3xl font-black text-slate-900">Dossiers KYC Vendeurs</h1>
                <p class="mt-1 text-xs text-slate-500">
                    Vérification biométrique Didit et arbitrage des décisions souveraines Ali-Kamer.
                </p>
            </div>

            {{-- Recherche rapide --}}
            <form method="GET" action="{{ route('admin.kyc.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                @if($filter)
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <div class="relative">
                    <input type="text" name="q" value="{{ $search ?? '' }}"
                           placeholder="Rechercher nom, email, CNI..."
                           class="w-64 rounded-2xl border border-slate-200 bg-white py-2 pl-9 pr-4 text-xs font-medium text-slate-800 placeholder-slate-400 focus:border-[#006837] focus:outline-none focus:ring-1 focus:ring-[#006837]" />
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </div>
                <button type="submit" class="rounded-2xl bg-[#006837] px-4 py-2 text-xs font-black text-white hover:bg-[#004d28]">
                    Filtrer
                </button>
                @if($search)
                    <a href="{{ route('admin.kyc.index', ['status' => $status, 'filter' => $filter]) }}" class="text-xs text-slate-400 hover:text-slate-600">Effacer</a>
                @endif
            </form>
        </div>

        {{-- Onglets de statuts principaux --}}
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-none">
            @php
                $tabs = [
                    'all'       => ['label' => 'Tous les dossiers', 'count' => $counts['all'] ?? 0],
                    'pending'   => ['label' => 'En attente', 'count' => $counts['pending'] ?? 0],
                    'reviewing' => ['label' => 'En revue humaine', 'count' => $counts['reviewing'] ?? 0],
                    'approved'  => ['label' => 'Approuvés', 'count' => $counts['approved'] ?? 0],
                    'rejected'  => ['label' => 'Rejetés', 'count' => $counts['rejected'] ?? 0],
                    'challenge' => ['label' => 'Challengés', 'count' => null],
                    'restricted'=> ['label' => 'Restreints', 'count' => null],
                    'blocked'   => ['label' => 'Bloqués / Ban', 'count' => null],
                ];
            @endphp

            @foreach($tabs as $tabKey => $tab)
                <a href="{{ route('admin.kyc.index', ['status' => $tabKey, 'filter' => $filter, 'q' => $search]) }}"
                   class="inline-flex shrink-0 items-center gap-1.5 rounded-2xl px-4 py-2 text-xs font-bold transition-all {{ $status === $tabKey ? 'bg-[#006837] text-white shadow-md shadow-[#006837]/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    <span>{{ $tab['label'] }}</span>
                    @if($tab['count'] !== null)
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-black {{ $status === $tabKey ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                            {{ $tab['count'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Filtres d'anomalies et signaux de risque --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
            <div class="flex items-center gap-2 mb-2 text-[11px] font-black uppercase tracking-wider text-slate-400">
                <span>⚡ Filtres de risque & alertes Didit :</span>
            </div>
            <div class="flex flex-wrap gap-2 text-xs">
                @php
                    $riskFilters = [
                        ''                  => 'Aucun filtre spécifique',
                        'low_face_match'    => 'Face Match < 70%',
                        'low_liveness'      => 'Liveness < 70%',
                        'doc_issue'         => 'Document problématique',
                        'high_risk'         => 'Profil Risque HIGH/CRITICAL',
                        'low_trust'         => 'TrustScore < 50',
                        'identity_matched'  => 'Identité déjà connue / Liée',
                        'warnings'          => 'Avertissements Didit',
                        'anomalies'         => 'Anomalies de sécurité (VPN/Scores)',
                    ];
                @endphp

                @foreach($riskFilters as $rfKey => $rfLabel)
                    <a href="{{ route('admin.kyc.index', ['status' => $status, 'filter' => $rfKey, 'q' => $search]) }}"
                       class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ ($filter ?? '') === $rfKey ? 'bg-amber-500 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $rfLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Tableau des dossiers --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[10px] font-black uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Vendeur & Boutique</th>
                            <th class="px-5 py-4">Statut Ali-Kamer</th>
                            <th class="px-5 py-4">Décision Admin</th>
                            <th class="px-5 py-4">Session Didit</th>
                            <th class="px-5 py-4">Biométrie (Didit)</th>
                            <th class="px-5 py-4">Document</th>
                            <th class="px-5 py-4">Confiance Ali-Kamer</th>
                            <th class="px-5 py-4">Dernière MàJ</th>
                            <th class="px-5 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dossiers as $kyc)
                            @php
                                $statusBadges = [
                                    'pending'   => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'reviewing' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'approved'  => 'bg-emerald-50 text-[#006837] border-emerald-200',
                                    'rejected'  => 'bg-red-50 text-red-700 border-red-200',
                                ];

                                $decisionColors = [
                                    'APPROVE'   => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                    'REJECT'    => 'text-red-700 bg-red-50 border-red-200',
                                    'REVIEW'    => 'text-amber-700 bg-amber-50 border-amber-200',
                                    'CHALLENGE' => 'text-orange-700 bg-orange-50 border-orange-200',
                                    'RESTRICT'  => 'text-purple-700 bg-purple-50 border-purple-200',
                                    'BLOCK'     => 'text-red-900 bg-red-100 border-red-300',
                                ];

                                $riskColors = [
                                    'LOW'      => 'text-emerald-700 bg-emerald-50',
                                    'MEDIUM'   => 'text-blue-700 bg-blue-50',
                                    'HIGH'     => 'text-orange-700 bg-orange-50',
                                    'CRITICAL' => 'text-red-700 bg-red-100',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                {{-- Vendeur --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 shrink-0 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-600 uppercase border border-slate-200">
                                            {{ substr($kyc->user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $kyc->user->name }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $kyc->user->email }}</div>
                                            @if($kyc->user->shop)
                                                <div class="text-[10px] font-semibold text-emerald-700">🏪 {{ $kyc->user->shop->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Statut Ali-Kamer --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-xl border px-2.5 py-1 text-[11px] font-black uppercase {{ $statusBadges[$kyc->status] ?? 'bg-slate-50 text-slate-600' }}">
                                        {{ $kyc->status }}
                                    </span>
                                </td>

                                {{-- Décision Admin --}}
                                <td class="px-5 py-4">
                                    @if($kyc->admin_decision)
                                        <span class="inline-flex rounded-lg border px-2 py-0.5 text-[10px] font-black uppercase tracking-wider {{ $decisionColors[$kyc->admin_decision] ?? 'bg-slate-50 text-slate-600' }}">
                                            {{ $kyc->admin_decision }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                {{-- Session Didit --}}
                                <td class="px-5 py-4">
                                    <div>
                                        <span class="font-bold text-slate-800">{{ $kyc->didit_session_status ?: 'Non démarré' }}</span>
                                    </div>
                                    <div class="font-mono text-[10px] text-slate-400 truncate max-w-[120px]">
                                        {{ $kyc->didit_session_id }}
                                    </div>
                                </td>

                                {{-- Biométrie Didit --}}
                                <td class="px-5 py-4 space-y-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] text-slate-400">Face Match:</span>
                                        <span class="font-bold {{ ($kyc->face_match_score !== null && $kyc->face_match_score < 70) ? 'text-red-600' : 'text-slate-700' }}">
                                            {{ $kyc->face_match_score !== null ? $kyc->face_match_score . '%' : ($kyc->face_match_status ?: '—') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] text-slate-400">Liveness:</span>
                                        <span class="font-bold {{ ($kyc->liveness_score !== null && $kyc->liveness_score < 70) ? 'text-red-600' : 'text-slate-700' }}">
                                            {{ $kyc->liveness_score !== null ? $kyc->liveness_score . '%' : ($kyc->liveness_status ?: '—') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Document --}}
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-800">
                                        {{ $kyc->document_status ?: 'En attente' }}
                                    </div>
                                    <div class="text-[10px] font-mono text-slate-500">
                                        {{ $kyc->document_number_masked ?: ($kyc->nationality ?? '—') }}
                                    </div>
                                    @if(!empty($kyc->warnings))
                                        <span class="inline-block mt-0.5 rounded px-1.5 py-0.2 bg-amber-100 text-amber-800 text-[9px] font-bold">
                                            ⚠️ {{ count($kyc->warnings) }} alerte(s)
                                        </span>
                                    @endif
                                </td>

                                {{-- Confiance Ali-Kamer --}}
                                <td class="px-5 py-4 space-y-1">
                                    <div class="flex items-center gap-1">
                                        <span class="text-[10px] text-slate-400">Score:</span>
                                        <span class="font-black text-slate-900">{{ $kyc->user->trust_score ?? 50 }}/100</span>
                                    </div>
                                    <div>
                                        <span class="rounded px-2 py-0.5 text-[9px] font-extrabold uppercase {{ $riskColors[$kyc->user->risk_profile ?? 'LOW'] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $kyc->user->risk_profile ?? 'LOW' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td class="px-5 py-4 text-[11px] text-slate-500">
                                    {{ $kyc->updated_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>

                                {{-- Action --}}
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.kyc.show', $kyc) }}"
                                       class="inline-flex items-center gap-1 rounded-xl bg-slate-100 hover:bg-[#006837] hover:text-white px-3 py-1.5 font-black text-slate-700 transition">
                                        <span>Consulter</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-16 text-center text-slate-400">
                                    <div class="text-3xl mb-2">📁</div>
                                    <div class="font-bold text-sm">Aucun dossier trouvé</div>
                                    <div class="text-xs text-slate-400 mt-1">Modifiez vos critères de recherche ou réinitialisez les filtres.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($dossiers->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 bg-slate-50">
                    {{ $dossiers->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
