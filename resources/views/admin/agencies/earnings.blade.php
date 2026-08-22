@extends('base')
@section('title', 'Gains des agences')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-5xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Gains des agences</h1>

    {{-- Totaux --}}
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500 mb-1">Total en attente (toutes agences)</p>
            <p class="text-2xl font-extrabold text-gray-500">
                {{ number_format($totalAgencyPending, 0, ',', ' ') }} FCFA
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-5">
            <p class="text-xs text-indigo-500 mb-1">Total disponible (toutes agences)</p>
            <p class="text-2xl font-extrabold text-indigo-600">
                {{ number_format($totalAgencyAvailable, 0, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

    {{-- Table agences --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Agence</th>
                    <th class="text-left px-5 py-3">En attente</th>
                    <th class="text-left px-5 py-3">Disponible</th>
                    <th class="text-left px-5 py-3">Comptoirs</th>
                    <th class="text-left px-5 py-3">Statut</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($agencies as $agency)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $agency->name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $agency->cities->count() }} ville(s)
                            </p>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">
                            {{ number_format($agency->wallet_pending, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-5 py-4 text-sm font-semibold text-indigo-600">
                            {{ number_format($agency->wallet_available, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">
                            {{ $agency->counters_count }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $agency->is_active
                                   ? 'bg-emerald-100 text-emerald-700'
                                   : 'bg-red-100 text-red-600' }}">
                                {{ $agency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.agencies.history', $agency) }}"
                               class="text-indigo-600 hover:underline text-sm font-medium">
                                Détail →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                            Aucune agence.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $agencies->links() }}</div>

</div>
</div>
@endsection