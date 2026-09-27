<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification — Ali-Kamer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
    <div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-8 shadow-xl text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#006837]/10 text-[#006837] text-2xl font-black">✓</div>
        <h1 class="mt-5 text-2xl font-black text-slate-900">Vérification reçue</h1>
        <p class="mt-3 text-sm leading-6 text-slate-500">
            Votre parcours Didit est terminé. Ali-Kamer vérifie maintenant le résultat et les signaux d'identité associés à votre compte.
        </p>

        @if($kyc)
            <div class="mt-6 rounded-2xl bg-slate-50 p-5 text-left text-sm">
                <div class="flex justify-between gap-4"><span class="text-slate-500">État Didit</span><strong>{{ $kyc->didit_session_status }}</strong></div>
                <div class="mt-3 flex justify-between gap-4"><span class="text-slate-500">État Ali-Kamer</span><strong>{{ ucfirst($kyc->status) }}</strong></div>
            </div>
        @elseif($error)
            <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-left text-sm font-semibold text-amber-800">{{ $error }}</div>
        @endif

        <a href="{{ route('seller.kyc.pending') }}" class="mt-7 inline-flex rounded-2xl bg-[#006837] px-6 py-3 text-sm font-black text-white hover:bg-[#004d28]">Voir mon dossier</a>
    </div>
</body>
</html>
