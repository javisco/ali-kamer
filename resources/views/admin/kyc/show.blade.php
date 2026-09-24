@extends('layouts.admin')
@section('title', 'Dossier KYC')
@section('content')
<div class="min-h-screen bg-slate-50 py-8"><div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between gap-4 mb-6"><div><a href="{{ route('admin.kyc.index') }}" class="text-xs font-bold text-slate-400 hover:text-[#006837]">← Retour</a><h1 class="mt-2 text-2xl font-black text-slate-900">Dossier KYC — {{ $kyc->user->name }}</h1></div><span class="rounded-full bg-[#006837]/10 px-3 py-1.5 text-xs font-black text-[#006837]">{{ strtoupper($kyc->status) }}</span></div>

    <div class="grid lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Identité Didit</h2><div class="mt-5 grid sm:grid-cols-2 gap-4 text-sm">
                <div><div class="text-xs text-slate-400">Nom extrait</div><div class="mt-1 font-bold">{{ $kyc->full_name ?: '—' }}</div></div>
                <div><div class="text-xs text-slate-400">Document</div><div class="mt-1 font-bold">{{ $kyc->document_number_masked ?: '—' }}</div></div>
                <div><div class="text-xs text-slate-400">Naissance</div><div class="mt-1 font-bold">{{ $kyc->date_of_birth ?: '—' }}</div></div>
                <div><div class="text-xs text-slate-400">Nationalité</div><div class="mt-1 font-bold">{{ $kyc->nationality ?: '—' }}</div></div>
            </div></section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Signaux du workflow</h2><div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach([['Document',$kyc->document_status],['Liveness',$kyc->liveness_status],['Face Match',$kyc->face_match_status],['IP Analysis',$kyc->ip_analysis_status]] as [$label,$value])
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4"><div class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $label }}</div><div class="mt-2 text-xs font-black text-slate-800">{{ $value ?: '—' }}</div></div>
                @endforeach
            </div></section>

            @if(!empty($kyc->warnings))
                <section class="rounded-3xl border border-amber-200 bg-amber-50 p-6"><h2 class="text-xs font-black uppercase tracking-widest text-amber-700">Avertissements Didit</h2><ul class="mt-4 space-y-2 text-xs text-amber-800">@foreach($kyc->warnings as $warning)<li>• {{ is_array($warning) ? json_encode($warning, JSON_UNESCAPED_UNICODE) : $warning }}</li>@endforeach</ul></section>
            @endif
        </div>

        <aside class="space-y-5">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Session</h2><dl class="mt-5 space-y-4 text-xs"><div><dt class="text-slate-400">Session ID</dt><dd class="mt-1 break-all font-mono font-bold">{{ $kyc->didit_session_id ?: '—' }}</dd></div><div><dt class="text-slate-400">Workflow</dt><dd class="mt-1 font-bold">{{ $kyc->didit_workflow_id ?: '—' }}</dd></div><div><dt class="text-slate-400">État Didit</dt><dd class="mt-1 font-bold">{{ $kyc->didit_session_status ?: '—' }}</dd></div><div><dt class="text-slate-400">Décision Ali-Kamer</dt><dd class="mt-1 font-black text-[#006837]">{{ $kyc->decision ?: 'REVIEW' }}</dd></div></dl></section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Action admin</h2><p class="mt-3 text-xs leading-5 text-slate-500">La validation manuelle reste disponible pour les dossiers placés en revue.</p><form action="{{ route('admin.kyc.approve',$kyc) }}" method="POST" class="mt-5">@csrf<button class="w-full rounded-2xl bg-[#006837] px-4 py-3 text-xs font-black text-white hover:bg-[#004d28]">✓ Approuver</button></form><form action="{{ route('admin.kyc.reject',$kyc) }}" method="POST" class="mt-3 space-y-2">@csrf<textarea name="reason" required minlength="10" rows="3" class="w-full rounded-2xl border border-slate-200 p-3 text-xs" placeholder="Motif du rejet..."></textarea><button class="w-full rounded-2xl bg-red-600 px-4 py-3 text-xs font-black text-white hover:bg-red-700">Rejeter</button></form></section>
        </aside>
    </div>
</div></div>
@endsection
