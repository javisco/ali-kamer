@extends('layouts.admin')
@section('title', 'Litiges')

@section('content')
    <div class="bg-gradient-to-b from-success-50/50 via-white to-slate-50 min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- En-tête de page --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-block rounded-full bg-[#CE1126]/10 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#CE1126]">
                        Gestion Admin
                    </span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-black tracking-tight text-slate-950">
                        Gestion des Litiges
                    </h1>
                </div>
            </div>

            {{-- Navigation par Onglets (Status) --}}
            <div class="flex flex-wrap gap-2 mb-6 p-1.5 bg-slate-100/80 rounded-2xl border border-slate-200/60 inline-flex">
                @foreach (['open' => 'Ouverts', 'seller_replied' => 'À examiner', 'under_review' => 'En cours', 'resolved' => 'Résolus'] as $s => $label)
                    <a href="?status={{ $s }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 flex items-center gap-2
                      {{ $status === $s 
                          ? 'bg-primary-600 text-white shadow-sm shadow-success-800/20' 
                          : 'bg-transparent text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                        <span>{{ $label }}</span>
                        @if (isset($counts[$s]) && $counts[$s] > 0)
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-black
                                {{ $status === $s ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $counts[$s] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Tableau des litiges --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3.5">Commande</th>
                                <th class="px-5 py-3.5">Acheteur</th>
                                <th class="px-5 py-3.5">Motif</th>
                                <th class="px-5 py-3.5">Date</th>
                                <th class="px-5 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($disputes as $dispute)
                                <tr class="hover:bg-success-50/40 transition-colors">
                                    <td class="px-5 py-4 font-black text-slate-900">
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#CE1126]"></span>
                                            {{ $dispute->order->reference }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 font-medium text-slate-700">
                                        {{ $dispute->order->buyer->name }}
                                    </td>
                                    <td class="px-5 py-4 text-slate-600">
                                        <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 text-xs font-bold border border-slate-200/60">
                                            {{ $dispute->typeLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs font-semibold text-slate-400">
                                        {{ $dispute->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.disputes.show', $dispute) }}"
                                            class="inline-flex items-center gap-1 text-xs font-black text-primary-600 hover:text-primary-700 hover:underline">
                                            <span>Examiner</span>
                                            <span>→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-3 text-xl">
                                            🛡️
                                        </div>
                                        <p class="text-sm font-bold text-slate-700">Aucun litige {{ $status }}</p>
                                        <p class="text-xs text-slate-400 mt-1">Tout fonctionne normalement dans cette catégorie.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if($disputes->hasPages())
                <div class="mt-6">
                    {{ $disputes->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection