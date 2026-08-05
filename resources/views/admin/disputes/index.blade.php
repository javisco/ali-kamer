@extends('base')
@section('title', 'Litiges')
@section('content')
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4">

            <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Litiges</h1>

            {{-- Onglets --}}
            <div class="flex gap-3 mb-6">
                @foreach (['open' => 'Ouverts', 'seller_replied' => 'À examiner', 'under_review' => 'En cours', 'resolved' => 'Résolus'] as $s => $label)
                    <a href="?status={{ $s }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition
                      {{ $status === $s ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }}
                        @if (isset($counts[$s]) && $counts[$s] > 0)
                            <span
                                class="ml-1 text-xs {{ $status === $s ? 'bg-white/20' : 'bg-gray-100' }} px-1.5 rounded-full">
                                {{ $counts[$s] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="text-left px-5 py-3">Commande</th>
                            <th class="text-left px-5 py-3">Acheteur</th>
                            <th class="text-left px-5 py-3">Motif</th>
                            <th class="text-left px-5 py-3">Date</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($disputes as $dispute)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-medium text-sm">
                                    {{ $dispute->order->reference }}
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $dispute->order->buyer->name }}
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $dispute->typeLabel() }}
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-400">
                                    {{ $dispute->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.disputes.show', $dispute) }}"
                                        class="text-indigo-600 hover:underline text-sm font-medium">
                                        Examiner →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                                    Aucun litige {{ $status }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $disputes->links() }}</div>
        </div>
    </div>
@endsection
