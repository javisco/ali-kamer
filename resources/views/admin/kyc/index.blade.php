@extends('layouts.admin')
@section('title', 'KYC vendeurs')
@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div><h1 class="text-2xl font-black text-slate-900">KYC vendeurs</h1><p class="mt-1 text-sm text-slate-500">Dossiers vérifiés avec Didit.</p></div>
            <div class="flex gap-2 flex-wrap">
                @foreach(['pending'=>'En attente','reviewing'=>'En revue','approved'=>'Approuvés','rejected'=>'Rejetés'] as $key=>$label)
                    <a href="{{ route('admin.kyc.index', ['status'=>$key]) }}" class="rounded-xl px-4 py-2 text-xs font-black {{ $status===$key ? 'bg-[#006837] text-white' : 'bg-white border border-slate-200 text-slate-600' }}">{{ $label }} ({{ $counts[$key] ?? 0 }})</a>
                @endforeach
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 border-b border-slate-200"><tr>
                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Vendeur</th>
                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Provider</th>
                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Didit</th>
                <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-wider text-slate-500">Décision</th>
                <th class="px-5 py-4"></th>
            </tr></thead><tbody class="divide-y divide-slate-100">
            @forelse($dossiers as $kyc)
                <tr class="hover:bg-slate-50/70"><td class="px-5 py-4"><div class="font-bold text-slate-900">{{ $kyc->user->name }}</div><div class="text-xs text-slate-400">{{ $kyc->user->email }}</div></td>
                <td class="px-5 py-4 text-xs font-bold text-slate-600">{{ strtoupper($kyc->provider ?? 'local') }}</td>
                <td class="px-5 py-4"><span class="rounded-full bg-[#006837]/10 px-2.5 py-1 text-xs font-bold text-[#006837]">{{ $kyc->didit_session_status ?? '—' }}</span></td>
                <td class="px-5 py-4 text-xs font-black text-slate-700">{{ $kyc->decision ?? 'REVIEW' }}</td>
                <td class="px-5 py-4 text-right"><a href="{{ route('admin.kyc.show', $kyc) }}" class="text-xs font-black text-[#006837] hover:underline">Ouvrir →</a></td></tr>
            @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Aucun dossier dans cet état.</td></tr>
            @endforelse
            </tbody></table></div>
            <div class="border-t border-slate-100 px-5 py-4">{{ $dossiers->links() }}</div>
        </div>
    </div>
</div>
@endsection
