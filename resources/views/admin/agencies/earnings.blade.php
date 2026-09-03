@extends('layouts.admin')

@section('title', 'Gains des agences')

@section('content')
<div class="min-h-screen bg-[#FAF9F6] py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        {{-- En-tête --}}
        <div class="relative overflow-hidden bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
            <div class="h-1 bg-gradient-to-r from-[#00843D] via-[#FCD116] to-[#CE1126]"></div>

            <div class="p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#00843D]"></span>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#00843D]">
                                Réseau Ali-Kamer
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900">
                            Gains des agences
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Suivi financier de l’ensemble des agences partenaires.
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-2xl bg-[#00843D]/10 text-[#00843D] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v1m0 10v1m-6-6a6 6 0 1012 0 6 6 0 00-12 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Totaux --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

            <div class="relative overflow-hidden bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#FCD116]"></div>

                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">
                    Total en attente
                </p>

                <p class="text-2xl sm:text-3xl font-black text-gray-900 mt-2">
                    {{ number_format($totalAgencyPending, 0, ',', ' ') }}
                    <span class="text-xs font-bold text-gray-400">FCFA</span>
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    Toutes les agences
                </p>
            </div>

            <div class="relative overflow-hidden bg-white rounded-2xl border border-[#00843D]/15 shadow-sm p-5">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#00843D]"></div>

                <p class="text-[11px] font-bold uppercase tracking-wider text-[#00843D]">
                    Total disponible
                </p>

                <p class="text-2xl sm:text-3xl font-black text-[#00843D] mt-2">
                    {{ number_format($totalAgencyAvailable, 0, ',', ' ') }}
                    <span class="text-xs font-bold text-[#00843D]/70">FCFA</span>
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    Fonds disponibles pour les agences
                </p>
            </div>
        </div>

        {{-- Table agences --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-gray-900">
                        Agences partenaires
                    </h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">
                        Situation financière actuelle
                    </p>
                </div>

                <div class="hidden sm:flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#00843D]"></span>
                    Réseau
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead class="bg-[#FAF9F6]">
                        <tr>
                            <th class="text-left px-5 py-3 text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Agence
                            </th>
                            <th class="text-left px-5 py-3 text-[10px] font-black uppercase tracking-wider text-gray-400">
                                En attente
                            </th>
                            <th class="text-left px-5 py-3 text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Disponible
                            </th>
                            <th class="text-left px-5 py-3 text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Comptoirs
                            </th>
                            <th class="text-left px-5 py-3 text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Statut
                            </th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($agencies as $agency)
                            <tr class="group hover:bg-[#00843D]/[0.025] transition-colors">

                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-900 text-sm">
                                        {{ $agency->name }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $agency->cities->count() }} ville(s)
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-sm font-medium text-gray-600">
                                    {{ number_format($agency->wallet_pending, 0, ',', ' ') }}
                                    <span class="text-[10px] text-gray-400">FCFA</span>
                                </td>

                                <td class="px-5 py-4 text-sm font-black text-[#00843D]">
                                    {{ number_format($agency->wallet_available, 0, ',', ' ') }}
                                    <span class="text-[10px] font-bold text-[#00843D]/60">FCFA</span>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $agency->counters_count }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold
                                        {{ $agency->is_active
                                            ? 'bg-[#00843D]/10 text-[#00843D]'
                                            : 'bg-red-50 text-[#CE1126]' }}">

                                        <span class="w-1.5 h-1.5 rounded-full
                                            {{ $agency->is_active ? 'bg-[#00843D]' : 'bg-[#CE1126]' }}">
                                        </span>

                                        {{ $agency->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.agencies.history', $agency) }}"
                                       class="inline-flex items-center gap-1 text-[#00843D] hover:text-[#006B32] text-xs font-bold transition">
                                        Détail
                                        <span class="group-hover:translate-x-0.5 transition-transform">→</span>
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-14 text-center">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                        </svg>
                                    </div>

                                    <p class="text-sm font-bold text-gray-700">
                                        Aucune agence
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Aucune agence partenaire n’est disponible.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">
            {{ $agencies->links() }}
        </div>

    </div>
</div>
@endsection