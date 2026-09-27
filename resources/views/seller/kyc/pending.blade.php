<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC en cours — Ali-Kamer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
<div class="w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-7 sm:p-10 shadow-xl">
    <div class="flex items-center gap-3">
        <div class="h-14 w-14 rounded-2xl bg-[#006837]/10 flex items-center justify-center text-[#006837] font-black">KYC</div>
        <div><div class="font-black">ALI-KAMER</div><div class="text-xs text-slate-400">Vérification vendeur</div></div>
    </div>

    <div class="mt-10 text-center">
        <div class="mx-auto h-16 w-16 rounded-full border-4 border-[#006837]/15 border-t-[#006837] animate-spin"></div>
        <h1 class="mt-6 text-2xl font-black">Votre dossier est en cours de traitement</h1>
        <p class="mt-3 text-sm leading-6 text-slate-500">Didit a reçu votre parcours. Ali-Kamer met à jour automatiquement ce dossier lorsque le résultat est disponible.</p>
    </div>

    <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach([
            ['Document', $kyc->document_status],
            ['Liveness', $kyc->liveness_status],
            ['Face Match', $kyc->face_match_status],
            ['IP', $kyc->ip_analysis_status],
        ] as [$label, $value])
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $label }}</div>
                <div class="mt-2 text-xs font-bold text-slate-700">{{ $value ?: 'En attente' }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-2xl bg-[#006837]/5 border border-[#006837]/15 p-4 text-xs leading-5 text-slate-600">
        Session Didit : <span class="font-mono font-bold">{{ $kyc->didit_session_id }}</span>
    </div>

    <div class="mt-7 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('seller.kyc.create') }}" class="flex-1 rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-bold text-slate-700 hover:bg-slate-50">Retour</a>
        <a href="{{ route('seller.kyc.pending') }}" class="flex-1 rounded-2xl bg-[#006837] px-5 py-3 text-center text-sm font-black text-white hover:bg-[#004d28]">Actualiser</a>
    </div>
</div>
</body>
</html>
