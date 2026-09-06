@extends('layouts.admin')

@section('title', 'Gestion des dossiers KYC - Ali-Kamer')

@section('content')

<div class="min-h-screen bg-[#F7F7F2] py-6 px-3 sm:px-6">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- 1. BANNIÈRE EN-TÊTE ALI-KAMER -->
        <div class="bg-[#016837] text-white rounded-2xl p-5 sm:p-6 shadow-xs relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-xs px-3 py-1 rounded-full text-xs font-semibold text-white mb-2 border border-white/15">
                        <span class="w-2 h-2 rounded-full bg-[#F9A01B]"></span>
                        Administration
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-white">
                        Gestion des dossiers KYC
                    </h1>
                    <p class="text-white/80 text-xs sm:text-sm mt-0.5 font-medium">
                        Examinez et gérez les demandes de vérification d'identité des vendeurs.
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. ALERTE DE SUCCÈS -->
        @if(session('success'))
            <div class="bg-[#016837]/10 border border-[#016837]/20 text-[#016837] rounded-xl px-4 py-3 text-xs sm:text-sm font-bold flex items-center gap-2.5 shadow-xs">
                <svg class="w-5 h-5 shrink-0 text-[#016837]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- 3. STATISTIQUES (KPIs) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- En attente -->
            <div class="bg-white border border-[#F9A01B]/40 rounded-2xl p-5 shadow-xs">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#F9A01B] mb-1">
                    En attente
                </p>
                <h2 class="text-3xl font-black text-[#0a1b12]">
                    {{ $counts['pending'] }}
                </h2>
            </div>

            <!-- Approuvés -->
            <div class="bg-white border border-[#016837]/30 rounded-2xl p-5 shadow-xs">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#016837] mb-1">
                    Approuvés
                </p>
                <h2 class="text-3xl font-black text-[#016837]">
                    {{ $counts['approved'] }}
                </h2>
            </div>

            <!-- Rejetés -->
            <div class="bg-white border border-[#E30613]/30 rounded-2xl p-5 shadow-xs">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#E30613] mb-1">
                    Rejetés
                </p>
                <h2 class="text-3xl font-black text-[#E30613]">
                    {{ $counts['rejected'] }}
                </h2>
            </div>

        </div>

        <!-- 4. FILTRES -->
        <div class="flex flex-wrap gap-2.5">

            @foreach ([
                'pending' => 'En attente',
                'approved' => 'Approuvés',
                'rejected' => 'Rejetés'
            ] as $s => $label)

                <a href="?status={{ $s }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs
                    {{ $status === $s
                        ? 'bg-[#016837] text-white'
                        : 'bg-white border border-gray-200 text-[#0a1b12] hover:bg-[#F7F7F2]' }}">

                    <span>{{ $label }}</span>

                    <span class="rounded-full px-2 py-0.5 text-[10px] font-black
                        {{ $status === $s ? 'bg-[#F9A01B] text-[#0a1b12]' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts[$s] }}
                    </span>

                </a>

            @endforeach

        </div>

        <!-- 5. TABLEAU DE DONNÉES -->
        <div class="overflow-hidden rounded-2xl bg-white border border-gray-200 shadow-xs">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-[#F7F7F2]">

                        <tr class="text-left text-[11px] font-black uppercase tracking-wider text-[#0a1b12]">

                            <th class="px-6 py-3.5">
                                Vendeur
                            </th>

                            <th class="px-6 py-3.5">
                                Téléphone
                            </th>

                            <th class="px-6 py-3.5">
                                Date de soumission
                            </th>

                            <th class="px-6 py-3.5 text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 text-xs">

                        @forelse($dossiers as $kyc)

                            <tr class="hover:bg-[#F7F7F2]/50 transition">

                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#0a1b12]">
                                        {{ $kyc->user->name }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-semibold text-gray-600">
                                    {{ $kyc->user->phone }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-500">
                                    {{ $kyc->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.kyc.show', $kyc) }}"
                                        class="inline-flex items-center rounded-xl bg-[#F9A01B] hover:bg-[#e08e14] active:scale-98 px-4 py-2 text-xs font-black text-[#0a1b12] transition shadow-xs uppercase tracking-wider">
                                        Examiner
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="py-16 text-center">

                                    <div class="w-12 h-12 bg-[#F7F7F2] rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>

                                    <p class="text-sm font-bold text-[#0a1b12]">
                                        Aucun dossier trouvé
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Aucun dossier {{ strtolower($label ?? $status) }} n'est disponible actuellement.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        {{-- Pagination --}}
        @if($dossiers->hasPages())
            <div class="mt-4">
                {{ $dossiers->links() }}
            </div>
        @endif

    </div>
</div>

@endsection