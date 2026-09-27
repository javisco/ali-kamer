<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>KYC à reprendre — Ali-Kamer</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
<div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white p-8 shadow-xl">
    <div class="h-14 w-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-black">!</div>
    <h1 class="mt-6 text-2xl font-black">La vérification doit être reprise</h1>
    <p class="mt-3 text-sm leading-6 text-slate-500">Votre précédent parcours n'a pas permis de valider votre identité. Vous pouvez recommencer le parcours Didit.</p>
    @if($kyc->rejection_reason)
        <div class="mt-6 rounded-2xl bg-red-50 border border-red-100 p-4 text-sm text-red-700"><strong>Motif :</strong> {{ $kyc->rejection_reason }}</div>
    @endif
    <a href="{{ route('seller.kyc.create') }}" class="mt-7 block rounded-2xl bg-[#006837] px-5 py-3 text-center text-sm font-black text-white hover:bg-[#004d28]">Recommencer la vérification</a>
</div>
</body>
</html>
