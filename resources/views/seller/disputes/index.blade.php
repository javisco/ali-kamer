@extends('base')
@section('title', 'Mes litiges')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
<div class="max-w-4xl mx-auto px-4">

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Mes litiges</h1>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($disputes->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
            <p class="text-gray-400 text-sm">Aucun litige pour l'instant.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="text-left px-5 py-3">Commande</th>
                        <th class="text-left px-5 py-3">Motif</th>
                        <th class="text-left px-5 py-3">Statut</th>
                        <th class="text-left px-5 py-3">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($disputes as $dispute)
                        @php
                            $statusConfig = [
                                'open'           => ['label' => 'En attente de votre réponse', 'class' => 'bg-red-100 text-red-700'],
                                'seller_replied' => ['label' => 'Réponse envoyée',             'class' => 'bg-blue-100 text-blue-700'],
                                'under_review'   => ['label' => 'En cours d\'examen',          'class' => 'bg-purple-100 text-purple-700'],
                                'resolved'       => ['label' => 'Résolu',                      'class' => 'bg-emerald-100 text-emerald-700'],
                            ];
                            $sc = $statusConfig[$dispute->status] ?? ['label' => $dispute->status, 'class' => 'bg-gray-100 text-gray-500'];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 font-medium text-sm">
                                {{ $dispute->order->reference }}
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $dispute->typeLabel() }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc['class'] }}">
                                    {{ $sc['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-400">
                                {{ $dispute->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('buyer.disputes.show', $dispute) }}"
                                   class="text-indigo-600 hover:underline text-sm font-medium">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $disputes->links() }}</div>
    @endif

</div>
</div>
@endsection