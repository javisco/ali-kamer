@extends('layouts.admin')
@section('title', 'Gestion des sanctions & sécurité')
@section('content')
<div class="min-h-screen bg-slate-50 py-8" x-data="{
    createModalOpen: false,
    liftModalOpen: false,
    liftSanctionId: null,
    liftUserName: '',
    sanctionType: 'SUSPENSION',
    duration: '24h',
    customMinutes: 60,
    openLiftModal(id, name) {
        this.liftSanctionId = id;
        this.liftUserName = name;
        this.liftModalOpen = true;
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-teal-50 border border-teal-200 p-4 flex items-center justify-between text-teal-800">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-teal-600 hover:text-teal-900 text-sm font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800">
                <div class="font-bold text-sm mb-1">Des erreurs sont survenues :</div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- En-tête --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sanctions & Sécurité</h1>
                <p class="mt-1 text-sm text-slate-500">Supervision multi-signaux, suspensions temporaires, restrictions ciblées et bannissements.</p>
            </div>
            <div>
                <button type="button" @click="createModalOpen = true"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-xs font-black text-white shadow-sm hover:bg-primary-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Appliquer une sanction
                </button>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Suspensions actives</div>
                <div class="mt-2 text-2xl font-black text-amber-600">{{ $counts['active_suspensions'] ?? 0 }}</div>
                <div class="text-[11px] text-slate-400 mt-1">Temporaires avec fin programmée</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Bannissements actifs</div>
                <div class="mt-2 text-2xl font-black text-rose-600">{{ $counts['active_bans'] ?? 0 }}</div>
                <div class="text-[11px] text-slate-400 mt-1">Exclusions définitives</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Restrictions actives</div>
                <div class="mt-2 text-2xl font-black text-blue-600">{{ $counts['active_restrictions'] ?? 0 }}</div>
                <div class="text-[11px] text-slate-400 mt-1">Fonctionnalités bloquées</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Sanctions levées</div>
                <div class="mt-2 text-2xl font-black text-teal-600">{{ $counts['total_lifted'] ?? 0 }}</div>
                <div class="text-[11px] text-slate-400 mt-1">Réhabilitations admin</div>
            </div>
        </div>

        {{-- Filtres & Recherche --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-slate-400 mr-1">Statut:</span>
                @foreach(['active' => 'Actives', 'expired' => 'Expirées', 'lifted' => 'Levées', 'all' => 'Toutes'] as $stKey => $stLabel)
                    <a href="{{ route('admin.sanctions.index', array_merge(request()->query(), ['status' => $stKey])) }}"
                       class="rounded-xl px-3 py-1.5 text-xs font-bold transition {{ $status === $stKey ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $stLabel }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.sanctions.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $status }}">
                <select name="type" class="rounded-xl border-slate-200 text-xs font-bold text-slate-700 py-1.5 px-3 focus:ring-primary-500">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Tous types</option>
                    <option value="suspension" {{ $type === 'suspension' ? 'selected' : '' }}>Suspensions</option>
                    <option value="ban" {{ $type === 'ban' ? 'selected' : '' }}>Bannissements</option>
                    <option value="restriction" {{ $type === 'restriction' ? 'selected' : '' }}>Restrictions</option>
                </select>
                <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher utilisateur..."
                       class="rounded-xl border-slate-200 text-xs py-1.5 px-3 w-48 sm:w-64 focus:ring-primary-500">
                <button type="submit" class="rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">Filtrer</button>
            </form>
        </div>

        {{-- Table des Sanctions --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Utilisateur</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Type & Détails</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Statut</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Motif</th>
                            <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Période</th>
                            <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sanctions as $sanction)
                            <tr class="hover:bg-slate-50/70 transition">
                                {{-- Utilisateur --}}
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-900">{{ $sanction->user?->name ?? '—' }}</div>
                                    <div class="text-xs text-slate-400">{{ $sanction->user?->phone ?? $sanction->user?->email }}</div>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                            {{ strtoupper($sanction->user?->role ?? '') }}
                                        </span>
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold {{ $sanction->user?->status === 'active' ? 'bg-teal-50 text-teal-700' : 'bg-rose-50 text-rose-700' }}">
                                            Statut actuel: {{ $sanction->user?->status ?? '—' }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Type --}}
                                <td class="px-5 py-4">
                                    @if($sanction->type === 'SUSPENSION')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-1 text-xs font-bold text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            SUSPENSION
                                        </span>
                                    @elseif($sanction->type === 'BAN')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-200 px-2.5 py-1 text-xs font-bold text-rose-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            BAN DÉFINITIF
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-bold text-blue-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            RESTRICTION
                                        </span>
                                    @endif

                                    @if($sanction->restrictions->isNotEmpty())
                                        <div class="mt-1.5 flex flex-wrap gap-1">
                                            @foreach($sanction->restrictions as $rest)
                                                <span class="text-[10px] bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-mono">{{ $rest->restriction_code }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td class="px-5 py-4">
                                    @if($sanction->status === 'active')
                                        <span class="inline-block rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Actif</span>
                                    @elseif($sanction->status === 'expired')
                                        <span class="inline-block rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">Expiré</span>
                                    @elseif($sanction->status === 'lifted')
                                        <span class="inline-block rounded-full bg-teal-50 px-2.5 py-1 text-xs font-bold text-teal-700">Levé par admin</span>
                                    @endif
                                </td>

                                {{-- Motif --}}
                                <td class="px-5 py-4 max-w-xs">
                                    <div class="text-xs text-slate-700 line-clamp-2" title="{{ $sanction->reason }}">{{ $sanction->reason }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-1">{{ $sanction->reason_code }}</div>
                                </td>

                                {{-- Période --}}
                                <td class="px-5 py-4 text-xs text-slate-600 whitespace-nowrap">
                                    <div>Début : <span class="font-bold text-slate-800">{{ $sanction->starts_at?->format('d/m/Y H:i') }}</span></div>
                                    @if($sanction->expires_at)
                                        <div class="mt-0.5">Fin : <span class="font-bold {{ $sanction->isActive() && $sanction->expires_at->isPast() ? 'text-rose-600' : 'text-slate-800' }}">{{ $sanction->expires_at->format('d/m/Y H:i') }}</span></div>
                                    @else
                                        <div class="mt-0.5 text-slate-400">Permanente</div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    @if($sanction->isActive())
                                        <button type="button"
                                                @click="openLiftModal({{ $sanction->id }}, '{{ addslashes($sanction->user?->name ?? 'cet utilisateur') }}')"
                                                class="rounded-xl border border-teal-300 bg-teal-50 px-3 py-1.5 text-xs font-black text-teal-700 hover:bg-teal-100 transition">
                                            Lever la sanction
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400 font-bold">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">
                                    Aucune sanction ne correspond à ces critères.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $sanctions->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL: NOUVELLE SANCTION --}}
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="createModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="createModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="createModalOpen" x-transition.scale class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="{{ route('admin.sanctions.store') }}" class="p-6">
                    @csrf
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <h3 class="text-lg font-black text-slate-900">Appliquer une sanction</h3>
                        <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
                    </div>

                    {{-- ID Utilisateur --}}
                    <div class="mb-4">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">ID Utilisateur <span class="text-rose-500">*</span></label>
                        <input type="number" name="user_id" required placeholder="Ex: 42"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-primary-500 focus:ring-primary-500">
                        <p class="mt-1 text-[11px] text-slate-400">Identifiant numérique de l'utilisateur Ali-Kamer.</p>
                    </div>

                    {{-- Type de Sanction --}}
                    <div class="mb-4">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Type de sanction <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer rounded-xl border p-2.5 text-center text-xs font-bold transition"
                                   :class="sanctionType === 'SUSPENSION' ? 'border-amber-500 bg-amber-50 text-amber-800' : 'border-slate-200 text-slate-600'">
                                <input type="radio" name="type" value="SUSPENSION" class="sr-only" x-model="sanctionType">
                                Suspension
                            </label>
                            <label class="cursor-pointer rounded-xl border p-2.5 text-center text-xs font-bold transition"
                                   :class="sanctionType === 'RESTRICTION' ? 'border-blue-500 bg-blue-50 text-blue-800' : 'border-slate-200 text-slate-600'">
                                <input type="radio" name="type" value="RESTRICTION" class="sr-only" x-model="sanctionType">
                                Restriction
                            </label>
                            <label class="cursor-pointer rounded-xl border p-2.5 text-center text-xs font-bold transition"
                                   :class="sanctionType === 'BAN' ? 'border-rose-500 bg-rose-50 text-rose-800' : 'border-slate-200 text-slate-600'">
                                <input type="radio" name="type" value="BAN" class="sr-only" x-model="sanctionType">
                                Ban définitif
                            </label>
                        </div>
                    </div>

                    {{-- Durée de la suspension --}}
                    <div class="mb-4" x-show="sanctionType === 'SUSPENSION'">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Durée de la suspension <span class="text-rose-500">*</span></label>
                        <select name="duration" x-model="duration" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="24h">24 heures (1 jour)</option>
                            <option value="3d">3 jours</option>
                            <option value="7d">7 jours (1 semaine)</option>
                            <option value="14d">14 jours (2 semaines)</option>
                            <option value="30d">30 jours (1 mois)</option>
                            <option value="custom">Durée personnalisée (minutes)</option>
                        </select>

                        <div class="mt-2" x-show="duration === 'custom'">
                            <label class="block text-[11px] font-bold text-slate-500 mb-1">Nombre de minutes :</label>
                            <input type="number" name="custom_duration_minutes" x-model="customMinutes" min="1" max="525600"
                                   class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    {{-- Restrictions ciblées --}}
                    <div class="mb-4" x-show="sanctionType === 'RESTRICTION' || sanctionType === 'SUSPENSION'">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Restrictions ciblées</label>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-100 hover:bg-slate-50">
                                <input type="checkbox" name="restrictions[]" value="cannot_sell" class="rounded text-primary-600">
                                <span>cannot_sell</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-100 hover:bg-slate-50">
                                <input type="checkbox" name="restrictions[]" value="cannot_publish" class="rounded text-primary-600">
                                <span>cannot_publish</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-100 hover:bg-slate-50">
                                <input type="checkbox" name="restrictions[]" value="cannot_buy" class="rounded text-primary-600">
                                <span>cannot_buy</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-100 hover:bg-slate-50">
                                <input type="checkbox" name="restrictions[]" value="cannot_create_order" class="rounded text-primary-600">
                                <span>cannot_create_order</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-100 hover:bg-slate-50">
                                <input type="checkbox" name="restrictions[]" value="cannot_withdraw" class="rounded text-primary-600">
                                <span>cannot_withdraw</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-100 hover:bg-slate-50">
                                <input type="checkbox" name="restrictions[]" value="cannot_message" class="rounded text-primary-600">
                                <span>cannot_message</span>
                            </label>
                        </div>
                    </div>

                    {{-- Code Motif --}}
                    <div class="mb-4">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Code motif</label>
                        <input type="text" name="reason_code" placeholder="Ex: kyc_repeated_failure, fraud_attempt, abuse"
                               class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-primary-500 focus:ring-primary-500 font-mono">
                    </div>

                    {{-- Motif / Justification --}}
                    <div class="mb-5">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Justification détaillée <span class="text-rose-500">*</span></label>
                        <textarea name="reason" rows="3" required placeholder="Expliquez la cause factuelle de la sanction..."
                                  class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Annuler</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-xs font-black text-white hover:bg-primary-700 shadow-sm transition">Confirmer la sanction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: LEVER LA SANCTION --}}
    <div x-show="liftModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="liftModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="liftModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="liftModalOpen" x-transition.scale class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form method="POST" :action="`/admin/sanctions/${liftSanctionId}/lever`" class="p-6">
                    @csrf
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <h3 class="text-base font-black text-slate-900">Lever la sanction</h3>
                        <button type="button" @click="liftModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
                    </div>

                    <p class="text-xs text-slate-500 mb-4">
                        Vous vous apprêtez à lever la sanction sur <strong class="text-slate-800" x-text="liftUserName"></strong>. Le statut du compte sera recalculé selon la règle d'or (ne redeviendra actif que si aucune autre sanction n'est en cours).
                    </p>

                    <div class="mb-5">
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-1.5">Motif de réhabilitation <span class="text-rose-500">*</span></label>
                        <textarea name="reason" rows="3" required placeholder="Expliquez pourquoi cette sanction est levée..."
                                  class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="liftModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Annuler</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-teal-600 text-xs font-black text-white hover:bg-teal-700 shadow-sm transition">Lever immédiatement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
