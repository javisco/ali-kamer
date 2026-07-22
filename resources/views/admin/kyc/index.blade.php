@extends('base')
@section('title', 'index')

@section('content')
    <div class="max-w-5xl mx-auto mt-20 py-8">
        @if (@session('success'))
            <div class="bg-green-400 h-15 text-center p-2 ">{{ session('success') }}</div>
        @endif
        <h1 class="text-2xl font-bold mb-6">Dossiers KYC</h1>

        {{-- Onglets statuts --}}
        <div class="flex gap-4 mb-6">
            @foreach (['pending' => 'En attente', 'approved' => 'Approuvés', 'rejected' => 'Rejetés'] as $s => $label)
                <a href="?status={{ $s }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium
                    {{ $status === $s ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                    {{ $label }}
                    <span class="ml-1 bg-white/20 px-1.5 rounded">{{ $counts[$s] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Table --}}
        <table class="w-full bg-white rounded-xl border border-gray-200">
            <thead class="bg-gray-50 text-sm text-gray-500">
                <tr>
                    <th class="text-left px-4 py-3">Vendeur</th>
                    <th class="text-left px-4 py-3">Téléphone</th>
                    <th class="text-left px-4 py-3">Soumis le</th>
                    <th class="text-left px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($dossiers as $kyc)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $kyc->user->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $kyc->user->phone }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $kyc->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.kyc.show', $kyc) }}" class="text-blue-600 hover:underline text-sm">
                                Examiner →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                            Aucun dossier {{ $status }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- <div class="mt-4">{{ $dossiers->links() }}</div> --}}
</div>
@endsection
