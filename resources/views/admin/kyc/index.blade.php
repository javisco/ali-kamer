@extends('layouts.admin')

@section('title', 'Gestion des dossiers KYC')

@section('content')

<div class="max-w-7xl mx-auto py-8">

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-300 bg-green-50 px-5 py-4 text-green-700 shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- En-tête -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Gestion des dossiers KYC
            </h1>

            <p class="mt-1 text-gray-500">
                Examinez et gérez les demandes de vérification d'identité des vendeurs.
            </p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 shadow-sm">
            <p class="text-sm text-yellow-700 font-medium">
                En attente
            </p>

            <h2 class="text-3xl font-bold mt-2 text-yellow-800">
                {{ $counts['pending'] }}
            </h2>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-xl p-5 shadow-sm">
            <p class="text-sm text-green-700 font-medium">
                Approuvés
            </p>

            <h2 class="text-3xl font-bold mt-2 text-green-800">
                {{ $counts['approved'] }}
            </h2>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-xl p-5 shadow-sm">
            <p class="text-sm text-red-700 font-medium">
                Rejetés
            </p>

            <h2 class="text-3xl font-bold mt-2 text-red-800">
                {{ $counts['rejected'] }}
            </h2>
        </div>

    </div>

    <!-- Filtres -->
    <div class="flex flex-wrap gap-3 mb-6">

        @foreach ([
            'pending' => 'En attente',
            'approved' => 'Approuvés',
            'rejected' => 'Rejetés'
        ] as $s => $label)

            <a href="?status={{ $s }}"
                class="px-5 py-2 rounded-lg font-medium transition

                {{ $status === $s
                    ? 'bg-blue-600 text-white shadow'
                    : 'bg-white border text-gray-600 hover:bg-gray-100' }}">

                {{ $label }}

                <span class="ml-2 rounded-full px-2 py-0.5 text-xs
                    {{ $status === $s ? 'bg-blue-500' : 'bg-gray-200 text-gray-700' }}">
                    {{ $counts[$s] }}
                </span>

            </a>

        @endforeach

    </div>

    <!-- Tableau -->
    <div class="overflow-hidden rounded-xl bg-white shadow-lg">

        <table class="min-w-full">

            <thead class="bg-gray-100">

                <tr class="text-left text-sm uppercase tracking-wide text-gray-600">

                    <th class="px-6 py-4">
                        Vendeur
                    </th>

                    <th class="px-6 py-4">
                        Téléphone
                    </th>

                    <th class="px-6 py-4">
                        Date de soumission
                    </th>

                    <th class="px-6 py-4 text-center">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

                @forelse($dossiers as $kyc)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">

                            <div class="font-semibold text-gray-800">
                                {{ $kyc->user->name }}
                            </div>

                        </td>

                        <td class="px-6 py-4 text-gray-600">

                            {{ $kyc->user->phone }}

                        </td>

                        <td class="px-6 py-4 text-gray-500">

                            {{ $kyc->created_at->format('d/m/Y H:i') }}

                        </td>

                        <td class="px-6 py-4 text-center">

                            <a href="{{ route('admin.kyc.show', $kyc) }}"
                                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">

                                Examiner

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="py-16 text-center">

                            <div class="text-5xl mb-3">
                                📂
                            </div>

                            <p class="text-lg font-semibold text-gray-600">
                                Aucun dossier trouvé
                            </p>

                            <p class="text-gray-400 mt-1">
                                Aucun dossier {{ strtolower($label ?? $status) }} n'est disponible actuellement.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $dossiers->links() }}
    </div>

</div>

@endsection