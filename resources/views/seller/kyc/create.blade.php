<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vérification KYC — Ali-Kamer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: {
                aliGreen: '#006837',
                aliGreenDark: '#004d28',
                aliSoft: '#F0FDF4'
            }, fontFamily: { sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'] } } }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900">
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-5xl overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl">
        <div class="grid lg:grid-cols-[1.05fr_.95fr]">
            <section class="p-6 sm:p-10 lg:p-12">
                <div class="flex items-center gap-3 mb-10">
                    <div class="h-14 w-14 rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/afrique.png') }}" alt="Ali-Kamer" class="h-11 w-auto object-contain">
                    </div>
                    <div>
                        <div class="font-black tracking-tight">ALI-KAMER</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Espace vendeur</div>
                    </div>
                </div>

                @if(session('fail'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ session('fail') }}</div>
                @endif

                <span class="inline-flex rounded-full bg-[#006837]/10 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-[#006837]">Étape obligatoire</span>
                <h1 class="mt-4 text-3xl sm:text-4xl font-black tracking-tight">Vérifiez votre identité</h1>
                <p class="mt-3 max-w-xl text-sm leading-7 text-slate-500">
                    Ali-Kamer utilise Didit pour effectuer la vérification d'identité de votre compte vendeur.
                    Vous serez redirigé vers une interface sécurisée pour terminer le contrôle.
                </p>

                <div class="mt-8 space-y-3">
                    @foreach([
                        ['Identité documentaire', 'Votre pièce d’identité sera vérifiée par Didit.'],
                        ['Liveness', 'Didit vérifie que la personne présente devant la caméra est réelle.'],
                        ['Face Match', 'Le visage est comparé avec la pièce d’identité.'],
                        ['Analyse IP', 'Des signaux techniques peuvent être utilisés pour sécuriser le parcours.'],
                    ] as [$title, $description])
                        <div class="flex gap-3 rounded-2xl border border-slate-200 p-4">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-[#006837]/10 text-[#006837] font-black">✓</div>
                            <div>
                                <div class="text-sm font-extrabold">{{ $title }}</div>
                                <div class="mt-1 text-xs leading-5 text-slate-500">{{ $description }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('seller.kyc.start') }}" method="POST" class="mt-8">
                    @csrf
                    <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 cursor-pointer">
                        <input type="checkbox" name="consent" value="1" required class="mt-1 h-4 w-4 rounded border-slate-300 text-[#006837] focus:ring-[#006837]">
                        <span class="text-xs leading-5 text-slate-600">
                            J'accepte de commencer la vérification d'identité et, lorsque nécessaire, le traitement de mes données biométriques dans le cadre de l'activation de mon compte vendeur. J'ai pris connaissance de la politique de confidentialité d'Ali-Kamer.
                        </span>
                    </label>

                    <button type="submit" class="mt-4 w-full rounded-2xl bg-[#006837] px-5 py-4 text-sm font-black text-white shadow-lg shadow-[#006837]/15 transition hover:bg-[#004d28] active:scale-[.99]">
                        Continuer vers Didit →
                    </button>
                </form>

                <p class="mt-4 text-center text-[11px] text-slate-400">Vos informations restent liées à votre compte vendeur Ali-Kamer.</p>
            </section>

            <aside class="hidden lg:flex bg-[#006837] p-12 text-white flex-col justify-between">
                <div>
                    <div class="inline-flex rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest">KYC sécurisé</div>
                    <h2 class="mt-5 text-3xl font-black leading-tight">Un seul parcours pour votre vérification.</h2>
                    <p class="mt-4 text-sm leading-7 text-white/75">Vous n'avez pas besoin d'envoyer votre CNI directement à Ali-Kamer. Le parcours de capture est géré par Didit selon le workflow KYC configuré.</p>
                </div>
                <div class="rounded-3xl border border-white/15 bg-white/10 p-6">
                    <div class="text-xs font-black uppercase tracking-widest text-white/60">Après la vérification</div>
                    <p class="mt-3 text-sm leading-6 text-white/85">Didit transmet le résultat à Ali-Kamer. Notre système vérifie ensuite les signaux d'identité internes avant d'activer la boutique.</p>
                </div>
            </aside>
        </div>
    </div>
</div>
</body>
</html>
