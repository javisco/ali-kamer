<!DOCTYPE html>

<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


<title>Vérification KYC — Ali-Kamer</title>

{{-- Tailwind via CDN : cette page est totalement autonome --}}
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                },
                colors: {
                    aliGreen: '#006837',
                    aliRed: '#EA2328',
                    aliYellow: '#FFC20E',
                }
            }
        }
    }
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

<style>
    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    html,
    body {
        min-height: 100%;
        margin: 0;
    }

    body {
        background: #f8fafc;
    }

    input[type="file"]::file-selector-button {
        cursor: pointer;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    .logo-float {
        animation: float 5s ease-in-out infinite;
    }

    @keyframes pulse-soft {
        0%, 100% {
            opacity: 1;
        }

        50% {
            opacity: .45;
        }
    }

    .pulse-soft {
        animation: pulse-soft 2s ease-in-out infinite;
    }
</style>


</head>

<body>

<main class="min-h-screen w-full flex flex-col lg:flex-row">

    {{-- =========================================================
         COLONNE GAUCHE — FORMULAIRE
    ========================================================== --}}
    <section
        class="w-full lg:w-1/2 min-h-screen bg-white flex flex-col p-5 sm:p-8 lg:p-10 xl:p-14 relative z-10">

        {{-- ================= HEADER ================= --}}
        <header class="flex items-center justify-between gap-4 mb-8 shrink-0">

            <a href="/"
               class="group flex items-center gap-3 active:scale-95 transition-transform">

                <div
                    class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl
                           bg-slate-50 border border-slate-100
                           shadow-sm flex items-center justify-center
                           group-hover:border-[#006837]/30
                           group-hover:shadow-md transition-all">

                    <img
                        src="{{ asset('images/afrique.png') }}"
                        alt="Ali-Kamer"
                        class="w-11 sm:w-12 h-auto object-contain">
                </div>

                <div class="hidden sm:block">
                    <p class="text-sm font-black text-slate-900 leading-none">
                        ALI-KAMER
                    </p>

                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-1">
                        Marketplace Cameroun
                    </p>
                </div>
            </a>

            <span
                class="shrink-0 text-[9px] sm:text-[10px] font-black
                       tracking-widest text-[#006837]
                       bg-[#006837]/10
                       px-3 py-2 rounded-full
                       uppercase border border-[#006837]/20">
                Espace vendeur
            </span>
        </header>


        {{-- ================= CONTENU ================= --}}
        <div class="flex-1 flex items-center">

            <div class="max-w-xl w-full mx-auto">

                {{-- TITRE --}}
                <div class="mb-7">

                    <div class="flex items-center gap-2 mb-2">

                        <span
                            class="w-2.5 h-2.5 rounded-full bg-[#EA2328] pulse-soft">
                        </span>

                        <span
                            class="text-[10px] sm:text-xs font-extrabold
                                   uppercase tracking-widest text-[#EA2328]">
                            Étape obligatoire
                        </span>

                    </div>

                    <h1
                        class="text-2xl sm:text-3xl xl:text-[34px]
                               font-black tracking-tight text-slate-900">
                        Vérification d'identité
                    </h1>

                    <p
                        class="text-xs sm:text-sm text-slate-500
                               mt-2 max-w-lg leading-relaxed font-medium">
                        Soumettez vos pièces justificatives afin d'activer
                        votre boutique et recevoir vos paiements en toute
                        sécurité.
                    </p>

                </div>


                {{-- ================= ALERTES ================= --}}

                @if (session('fail'))
                    <div
                        class="mb-5 rounded-2xl
                               border border-[#EA2328]/30
                               bg-[#EA2328]/5
                               p-4 flex items-start gap-3
                               text-sm text-[#EA2328] shadow-sm">

                        <svg
                            class="w-5 h-5 shrink-0 mt-0.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                        <span class="font-semibold">
                            {{ session('fail') }}
                        </span>

                    </div>
                @endif


                @if (session('success'))
                    <div
                        class="mb-5 rounded-2xl
                               border border-[#006837]/30
                               bg-[#006837]/5
                               p-4 flex items-start gap-3
                               text-sm text-[#006837] shadow-sm">

                        <svg
                            class="w-5 h-5 shrink-0 mt-0.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7" />

                        </svg>

                        <span class="font-semibold">
                            {{ session('success') }}
                        </span>

                    </div>
                @endif


                {{-- ================= FORMULAIRE ================= --}}
                <form
                    action="{{ route('seller.kyc.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5">

                    @csrf


                    {{-- CNI --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- RECTO --}}
                        <div>

                            <label
                                for="cni_front_url"
                                class="block mb-1.5 text-[10px]
                                       font-extrabold uppercase
                                       tracking-wider text-slate-700">

                                CNI — Recto

                                <span class="text-[#EA2328]">*</span>

                            </label>

                            <input
                                type="file"
                                name="cni_front_url"
                                id="cni_front_url"
                                accept="image/*"
                                required
                                class="w-full text-[11px] text-slate-500
                                       bg-slate-50 rounded-xl border p-2
                                       file:mr-2
                                       file:py-2
                                       file:px-3
                                       file:rounded-lg
                                       file:border-0
                                       file:text-[10px]
                                       file:font-extrabold
                                       file:bg-[#006837]
                                       file:text-white
                                       hover:file:bg-[#00522b]
                                       cursor-pointer transition
                                       focus:outline-none
                                       @error('cni_front_url')
                                           border-[#EA2328]
                                       @else
                                           border-slate-200
                                           focus:border-[#006837]
                                           focus:ring-4
                                           focus:ring-[#006837]/10
                                       @enderror">

                            @error('cni_front_url')
                                <p class="mt-1 text-[10px] text-[#EA2328] font-semibold">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- VERSO --}}
                        <div>

                            <label
                                for="cni_back_url"
                                class="block mb-1.5 text-[10px]
                                       font-extrabold uppercase
                                       tracking-wider text-slate-700">

                                CNI — Verso

                                <span class="text-[#EA2328]">*</span>

                            </label>

                            <input
                                type="file"
                                name="cni_back_url"
                                id="cni_back_url"
                                accept="image/*"
                                required
                                class="w-full text-[11px] text-slate-500
                                       bg-slate-50 rounded-xl border p-2
                                       file:mr-2
                                       file:py-2
                                       file:px-3
                                       file:rounded-lg
                                       file:border-0
                                       file:text-[10px]
                                       file:font-extrabold
                                       file:bg-[#006837]
                                       file:text-white
                                       hover:file:bg-[#00522b]
                                       cursor-pointer transition
                                       focus:outline-none
                                       @error('cni_back_url')
                                           border-[#EA2328]
                                       @else
                                           border-slate-200
                                           focus:border-[#006837]
                                           focus:ring-4
                                           focus:ring-[#006837]/10
                                       @enderror">

                            @error('cni_back_url')
                                <p class="mt-1 text-[10px] text-[#EA2328] font-semibold">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- SELFIE --}}
                    <div>

                        <label
                            for="selfie_url"
                            class="block mb-1 text-[10px]
                                   font-extrabold uppercase
                                   tracking-wider text-slate-700">

                            Selfie avec votre CNI

                            <span class="text-[#EA2328]">*</span>

                        </label>

                        <p class="text-[10px] text-slate-400 mb-2 font-medium">
                            Tenez votre document lisiblement à côté de votre
                            visage sans le masquer.
                        </p>

                        <input
                            type="file"
                            name="selfie_url"
                            id="selfie_url"
                            accept="image/*"
                            required
                            class="w-full text-[11px] text-slate-500
                                   bg-slate-50 rounded-xl border p-2
                                   file:mr-2
                                   file:py-2
                                   file:px-3
                                   file:rounded-lg
                                   file:border-0
                                   file:text-[10px]
                                   file:font-extrabold
                                   file:bg-[#006837]
                                   file:text-white
                                   hover:file:bg-[#00522b]
                                   cursor-pointer transition
                                   focus:outline-none
                                   @error('selfie_url')
                                       border-[#EA2328]
                                   @else
                                       border-slate-200
                                       focus:border-[#006837]
                                       focus:ring-4
                                       focus:ring-[#006837]/10
                                   @enderror">

                        @error('selfie_url')
                            <p class="mt-1 text-[10px] text-[#EA2328] font-semibold">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- RCCM --}}
                    <div>

                        <label
                            for="rccm_url"
                            class="block mb-1 text-[10px]
                                   font-extrabold uppercase
                                   tracking-wider text-slate-700">

                            RCCM ou Carte de contribuable

                            <span class="text-slate-400 font-medium normal-case">
                                (facultatif)
                            </span>

                        </label>

                        <p class="text-[10px] text-slate-400 mb-2 font-medium">
                            Formats acceptés : JPG, PNG ou PDF — 5 MB maximum.
                        </p>

                        <input
                            type="file"
                            name="rccm_url"
                            id="rccm_url"
                            accept="image/*,application/pdf"
                            class="w-full text-[11px] text-slate-500
                                   bg-slate-50 rounded-xl border p-2
                                   file:mr-2
                                   file:py-2
                                   file:px-3
                                   file:rounded-lg
                                   file:border-0
                                   file:text-[10px]
                                   file:font-extrabold
                                   file:bg-slate-200
                                   file:text-slate-700
                                   hover:file:bg-slate-300
                                   cursor-pointer transition
                                   focus:outline-none
                                   @error('rccm_url')
                                       border-[#EA2328]
                                   @else
                                       border-slate-200
                                       focus:border-[#006837]
                                       focus:ring-4
                                       focus:ring-[#006837]/10
                                   @enderror">

                        @error('rccm_url')
                            <p class="mt-1 text-[10px] text-[#EA2328] font-semibold">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- CONFIDENTIALITE --}}
                    <div
                        class="rounded-2xl
                               bg-slate-50
                               border border-slate-100
                               p-3.5 sm:p-4
                               flex items-start gap-3">

                        <div
                            class="w-9 h-9 rounded-xl
                                   bg-[#006837]/10
                                   flex items-center justify-center
                                   shrink-0">

                            <svg
                                class="w-4 h-4 text-[#006837]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

                            </svg>

                        </div>

                        <p
                            class="text-[10px] sm:text-xs
                                   text-slate-500
                                   leading-relaxed
                                   font-medium">

                            Vos données personnelles sont protégées et
                            utilisées uniquement pour la vérification de
                            votre identité et la conformité de votre
                            boutique.

                        </p>

                    </div>


                    {{-- SUBMIT --}}
                    <button
                        type="submit"
                        class="w-full rounded-2xl
                               bg-[#006837]
                               py-3.5 sm:py-4
                               text-xs sm:text-sm
                               font-extrabold text-white
                               shadow-lg shadow-[#006837]/20
                               hover:bg-[#00522b]
                               active:scale-[0.99]
                               transition-all
                               flex items-center justify-center gap-2
                               group">

                        <span>
                            Soumettre mon dossier KYC
                        </span>

                        <svg
                            class="w-4 h-4 text-[#FFC20E]
                                   transition-transform
                                   group-hover:translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />

                        </svg>

                    </button>

                </form>

            </div>

        </div>


        {{-- ================= FOOTER ================= --}}
        <footer
            class="pt-6 mt-8
                   border-t border-slate-100
                   flex items-center justify-between
                   gap-4 text-[10px] sm:text-xs
                   text-slate-400 shrink-0">

            <a
                href="/"
                class="inline-flex items-center gap-1.5
                       font-bold text-slate-500
                       hover:text-[#006837] transition-colors">

                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />

                </svg>

                <span>
                    Retour à l'accueil
                </span>

            </a>

            <span>
                Ali-Kamer &copy; {{ date('Y') }}
            </span>

        </footer>

    </section>


    {{-- =========================================================
         COLONNE DROITE — IDENTITÉ / SÉCURITÉ
    ========================================================== --}}
    <section
        class="hidden lg:flex
               w-1/2 min-h-screen
               relative overflow-hidden
               bg-gradient-to-br
               from-[#004d28]
               via-[#006837]
               to-[#780f12]
               text-white
               p-10 xl:p-14
               flex-col justify-between">

        {{-- DECORATIONS --}}
        <div
            class="absolute
                   -top-32 -right-32
                   w-[500px] h-[500px]
                   rounded-full
                   bg-[#FFC20E]/15
                   blur-3xl
                   pointer-events-none">
        </div>

        <div
            class="absolute
                   -bottom-32 -left-32
                   w-[500px] h-[500px]
                   rounded-full
                   bg-[#EA2328]/20
                   blur-3xl
                   pointer-events-none">
        </div>

        {{-- FILIGRANE LOGO --}}
        <div
            class="absolute
                   right-[-100px]
                   top-1/2
                   -translate-y-1/2
                   opacity-[0.07]
                   pointer-events-none">

            <img
                src="{{ asset('images/afrique.png') }}"
                alt=""
                class="w-[580px] h-auto object-contain">
        </div>


        {{-- ================= HEADER DROITE ================= --}}
        <div class="relative z-10">

            <div class="flex items-center justify-between">

                <span
                    class="inline-flex items-center gap-2
                           text-[10px]
                           font-black
                           tracking-widest
                           uppercase
                           text-[#FFC20E]
                           bg-black/20
                           px-4 py-2
                           rounded-full
                           backdrop-blur-md
                           border border-[#FFC20E]/30">

                    <span
                        class="w-2 h-2 rounded-full
                               bg-[#EA2328] pulse-soft">
                    </span>

                    Sécurité & conformité

                </span>


                <div
                    class="p-2.5
                           rounded-2xl
                           bg-white/10
                           backdrop-blur-md
                           border border-white/20
                           shadow-xl">

                    <img
                        src="{{ asset('images/afrique.png') }}"
                        alt="Ali-Kamer"
                        class="h-10 w-auto object-contain">
                </div>

            </div>


            <div class="mt-10">

                <h2
                    class="text-3xl xl:text-4xl
                           font-black
                           leading-[1.12]
                           tracking-tight">

                    Activez votre boutique
                    <br>

                    en toute
                    <span class="text-[#FFC20E]">
                        confiance.
                    </span>

                </h2>

                <p
                    class="text-sm
                           text-emerald-100/90
                           mt-4
                           max-w-lg
                           leading-relaxed">

                    La vérification KYC permet de protéger les acheteurs,
                    les vendeurs et l'ensemble de la communauté Ali-Kamer.
                    Elle garantit également une meilleure traçabilité
                    des transactions.

                </p>

            </div>

        </div>


        {{-- ================= GUIDE ================= --}}
        <div class="relative z-10 my-8">

            <div class="flex items-center gap-3 mb-5">

                <div class="h-px flex-1 bg-white/10"></div>

                <h3
                    class="text-[10px]
                           font-black
                           uppercase
                           tracking-widest
                           text-[#FFC20E]">

                    Validation du dossier

                </h3>

                <div class="h-px flex-1 bg-white/10"></div>

            </div>


            {{-- ETAPE 1 --}}
            <div
                class="flex items-start gap-4
                       p-4 mb-3
                       rounded-2xl
                       bg-white/5
                       backdrop-blur-md
                       border border-white/10
                       hover:bg-white/10
                       transition">

                <div
                    class="w-10 h-10
                           rounded-xl
                           bg-[#FFC20E]
                           text-slate-900
                           font-black
                           text-xs
                           flex items-center justify-center
                           shrink-0 shadow-md">

                    01

                </div>

                <div>

                    <h4 class="font-bold text-sm">
                        Des photos claires
                    </h4>

                    <p
                        class="text-[11px]
                               text-emerald-100/75
                               mt-1
                               leading-relaxed">

                        Évitez les photos floues, trop sombres ou avec
                        des reflets qui empêchent la lecture du document.

                    </p>

                </div>

            </div>


            {{-- ETAPE 2 --}}
            <div
                class="flex items-start gap-4
                       p-4 mb-3
                       rounded-2xl
                       bg-white/5
                       backdrop-blur-md
                       border border-white/10
                       hover:bg-white/10
                       transition">

                <div
                    class="w-10 h-10
                           rounded-xl
                           bg-[#EA2328]
                           text-white
                           font-black
                           text-xs
                           flex items-center justify-center
                           shrink-0 shadow-md">

                    02

                </div>

                <div>

                    <h4 class="font-bold text-sm">
                        Document entièrement visible
                    </h4>

                    <p
                        class="text-[11px]
                               text-emerald-100/75
                               mt-1
                               leading-relaxed">

                        Les quatre coins et toutes les informations
                        importantes de votre CNI doivent être visibles.

                    </p>

                </div>

            </div>


            {{-- ETAPE 3 --}}
            <div
                class="flex items-start gap-4
                       p-4
                       rounded-2xl
                       bg-white/5
                       backdrop-blur-md
                       border border-white/10
                       hover:bg-white/10
                       transition">

                <div
                    class="w-10 h-10
                           rounded-xl
                           bg-[#006837]
                           border border-emerald-300/30
                           text-white
                           font-black
                           text-xs
                           flex items-center justify-center
                           shrink-0 shadow-md">

                    03

                </div>

                <div>

                    <h4 class="font-bold text-sm">
                        Selfie avec votre CNI
                    </h4>

                    <p
                        class="text-[11px]
                               text-emerald-100/75
                               mt-1
                               leading-relaxed">

                        Gardez votre visage entièrement visible et
                        présentez votre document à côté de vous.

                    </p>

                </div>

            </div>

        </div>


        {{-- ================= FOOTER DROITE ================= --}}
        <div class="relative z-10">

            <div
                class="p-4
                       rounded-2xl
                       bg-black/20
                       border border-white/10
                       backdrop-blur-sm
                       flex items-center justify-between gap-4">

                <div>

                    <p
                        class="text-[10px]
                               text-emerald-100/80
                               font-medium">

                        Temps moyen de traitement

                    </p>

                    <p
                        class="text-sm
                               font-black
                               text-[#FFC20E]
                               mt-0.5">

                        24h à 48h ouvrées

                    </p>

                </div>


                <div class="flex items-center gap-1.5">

                    <span
                        class="w-3.5 h-3.5
                               rounded-full
                               bg-[#006837]
                               border border-white/40">
                    </span>

                    <span
                        class="w-3.5 h-3.5
                               rounded-full
                               bg-[#EA2328]
                               border border-white/40">
                    </span>

                    <span
                        class="w-3.5 h-3.5
                               rounded-full
                               bg-[#FFC20E]
                               border border-white/40">
                    </span>

                </div>

            </div>


            <p
                class="text-[10px]
                       text-center
                       text-emerald-100/60
                       mt-4">

                Besoin d'aide ?
                <span class="text-white font-semibold">
                    support@ali-kamer.cm
                </span>

            </p>

        </div>

    </section>

</main>


</body>
</html>
