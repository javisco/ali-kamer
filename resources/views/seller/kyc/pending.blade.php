<!DOCTYPE html>

<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


<title>Vérification KYC en cours — Ali-Kamer</title>

{{-- Tailwind autonome --}}
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    aliGreen: '#0D9488',
                    aliGreenDark: '#115E59',
                    aliRed: '#EF4444',
                    aliYellow: '#F59E0B',
                },
                fontFamily: {
                    sans: ['Plus Jakarta Sans', 'sans-serif'],
                }
            }
        }
    }
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet"
>

<style>
    * {
        box-sizing: border-box;
    }

    html,
    body {
        min-height: 100%;
        margin: 0;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background:
            radial-gradient(circle at 10% 10%, rgba(0, 104, 55, .07), transparent 28%),
            radial-gradient(circle at 90% 90%, rgba(234, 35, 40, .06), transparent 28%),
            #f8fafc;
    }

    .page-enter {
        animation: pageEnter .55s ease-out both;
    }

    .soft-pulse {
        animation: softPulse 2s ease-in-out infinite;
    }

    .floating {
        animation: floating 5s ease-in-out infinite;
    }

    @keyframes pageEnter {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.99);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes softPulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: .65;
            transform: scale(.92);
        }
    }

    @keyframes floating {
        0%, 100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    .glass {
        background: rgba(255, 255, 255, .10);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    @media (max-width: 1023px) {
        .mobile-scroll {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 104, 55, .25) transparent;
        }
    }
</style>


</head>

<body class="text-slate-900">


<main class="min-h-screen w-full flex items-center justify-center p-3 sm:p-5 lg:p-8">

    <div
        class="page-enter w-full max-w-6xl min-h-[620px] lg:h-[680px] bg-white rounded-[2rem] shadow-[0_25px_80px_rgba(15,23,42,.12)] overflow-hidden border border-slate-200/80 flex flex-col lg:flex-row"
    >

        {{-- =========================================================
            COLONNE GAUCHE
        ========================================================== --}}
        <section
            class="mobile-scroll w-full lg:w-[58%] p-5 sm:p-7 lg:p-9 flex flex-col bg-white"
        >

            {{-- Logo + statut --}}
            <div class="flex items-center justify-between gap-4 shrink-0">

                <a
                    href="/"
                    class="group flex items-center gap-2.5 transition-all duration-200 hover:-translate-y-0.5 active:scale-95"
                >
                    <div
                        class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm flex items-center justify-center overflow-hidden"
                    >
                        <img
                            src="{{ asset('images/afrique.png') }}"
                            alt="Ali-Kamer"
                            class="h-8 sm:h-9 w-auto object-contain"
                        >
                    </div>

                    <div class="hidden sm:block">
                        <p class="text-sm font-black tracking-tight text-slate-900">
                            Ali-Kamer
                        </p>
                        <p class="text-[9px] font-bold uppercase tracking-[.18em] text-slate-400">
                            Espace vendeur
                        </p>
                    </div>
                </a>

                <span
                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 bg-warning/10 border border-warning/30 text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-warning-800 whitespace-nowrap"
                >
                    <span class="relative flex h-2 w-2">
                        <span
                            class="absolute inline-flex h-full w-full rounded-full bg-danger opacity-70 animate-ping"
                        ></span>
                        <span
                            class="relative inline-flex h-2 w-2 rounded-full bg-danger"
                        ></span>
                    </span>
                    Examen en cours
                </span>

            </div>


            {{-- Contenu principal --}}
            <div class="flex-1 flex flex-col justify-center py-7 lg:py-5">

                {{-- Statut principal --}}
                <div
                    class="rounded-[1.4rem] border border-warning/30 bg-gradient-to-br from-warning/15 via-warning-50/50 to-slate-50 p-4 sm:p-5 flex items-center gap-4"
                >

                    <div class="relative shrink-0">

                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-warning/20 flex items-center justify-center text-2xl sm:text-3xl shadow-inner"
                        >
                            ⏳
                        </div>

                        <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4">
                            <span
                                class="absolute inline-flex h-full w-full rounded-full bg-danger opacity-60 animate-ping"
                            ></span>

                            <span
                                class="relative inline-flex h-4 w-4 rounded-full bg-danger border-2 border-white"
                            ></span>
                        </span>

                    </div>

                    <div class="min-w-0">
                        <p
                            class="text-[9px] sm:text-[10px] uppercase tracking-[.16em] font-black text-warning-700 mb-1"
                        >
                            Statut actuel
                        </p>

                        <h1
                            class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 leading-tight"
                        >
                            Dossier en traitement
                        </h1>

                        <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-1 leading-relaxed">
                            Vos documents ont bien été reçus et sont actuellement examinés par notre équipe.
                        </p>
                    </div>

                </div>


                {{-- Informations dossier --}}
                <div
                    class="mt-3.5 rounded-[1.4rem] border border-slate-200 bg-slate-50/70 p-4"
                >

                    <div class="flex items-center gap-2 mb-3">

                        <div
                            class="w-8 h-8 rounded-xl bg-primary-600/10 text-primary-600 flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-[10px] font-black uppercase tracking-wider text-slate-700">
                                Informations du dossier
                            </h2>

                            <p class="text-[9px] text-slate-400 font-medium">
                                Suivi de votre demande de vérification
                            </p>
                        </div>

                    </div>


                    <div class="space-y-2.5 text-[11px] sm:text-xs">

                        {{-- Date --}}
                        <div
                            class="flex items-center justify-between gap-4 pb-2.5 border-b border-slate-200/80"
                        >
                            <span class="text-slate-500 font-medium">
                                Date de soumission
                            </span>

                            <span class="font-bold text-slate-800 text-right">
                                {{ $kyc->created_at->format('d/m/Y à H:i') }}
                            </span>
                        </div>


                        {{-- Statut --}}
                        <div
                            class="flex items-center justify-between gap-4 pb-2.5 border-b border-slate-200/80"
                        >
                            <span class="text-slate-500 font-medium">
                                Statut du dossier
                            </span>

                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-warning/15 border border-warning/40 text-[9px] font-black text-warning-800 whitespace-nowrap"
                            >
                                <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                En traitement
                            </span>
                        </div>


                        {{-- Délai --}}
                        <div
                            class="flex items-center justify-between gap-4"
                        >
                            <span class="text-slate-500 font-medium">
                                Délai estimé
                            </span>

                            <span class="font-black text-primary-600 text-right">
                                24 à 48 h ouvrables
                            </span>
                        </div>

                    </div>

                </div>


                {{-- Notification --}}
                <div
                    class="mt-3.5 rounded-[1.3rem] border border-primary-600/20 bg-primary-600/5 p-3.5 flex items-start gap-3"
                >

                    <div
                        class="w-9 h-9 rounded-xl bg-primary-600 text-white flex items-center justify-center shrink-0 shadow-sm"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                            />
                        </svg>
                    </div>

                    <div>
                        <h3
                            class="text-[9px] font-black uppercase tracking-[.15em] text-primary-600"
                        >
                            Vous serez informé
                        </h3>

                        <p class="text-[10px] sm:text-[11px] text-slate-600 mt-1 leading-relaxed">
                            Une notification vous sera envoyée dès que votre dossier sera validé.
                            Vous pourrez également être informé par
                            <strong class="text-slate-700">SMS, WhatsApp ou e-mail</strong>.
                        </p>
                    </div>

                </div>


                {{-- Recommandations --}}
                <div
                    class="mt-3.5 rounded-[1.3rem] bg-slate-50 border border-slate-100 p-3.5"
                >

                    <h3
                        class="text-[9px] font-black uppercase tracking-[.15em] text-slate-500 mb-2"
                    >
                        Pendant l'attente
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-danger shrink-0"></span>
                            <span class="text-[10px] text-slate-500 font-medium">
                                Ne renvoyez pas un nouveau dossier
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-warning shrink-0"></span>
                            <span class="text-[10px] text-slate-500 font-medium">
                                Vérifiez vos e-mails et spams
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary-600 shrink-0"></span>
                            <span class="text-[10px] text-slate-500 font-medium">
                                Préparez vos premiers produits
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-400 shrink-0"></span>
                            <span class="text-[10px] text-slate-500 font-medium">
                                Consultez les ressources vendeur
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <div
                class="shrink-0 pt-4 border-t border-slate-100 flex items-center justify-between gap-4"
            >

                <a
                    href="/"
                    class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-black text-slate-500 hover:text-primary-600 transition-colors"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>

                    Retour à l'accueil
                </a>

                <span class="text-[9px] sm:text-[10px] font-semibold text-slate-400">
                    Ali-Kamer © {{ date('Y') }}
                </span>

            </div>

        </section>


        {{-- =========================================================
            COLONNE DROITE
        ========================================================== --}}
        <section
            class="hidden lg:flex relative w-[42%] overflow-hidden bg-gradient-to-br from-primary-800 via-primary-600 to-danger-800 text-white p-8 flex-col justify-between"
        >

            {{-- Effets lumineux --}}
            <div
                class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-warning/20 blur-3xl"
            ></div>

            <div
                class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-danger/25 blur-3xl"
            ></div>

            <div
                class="absolute top-1/2 right-[-120px] -translate-y-1/2 opacity-[.07] pointer-events-none"
            >
                <img
                    src="{{ asset('images/afrique.png') }}"
                    alt=""
                    class="w-[480px] h-auto object-contain"
                >
            </div>


            {{-- Header --}}
            <div class="relative z-10">

                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/20 border border-white/10 backdrop-blur-md"
                >
                    <span
                        class="w-2 h-2 rounded-full bg-warning soft-pulse"
                    ></span>

                    <span
                        class="text-[9px] font-black uppercase tracking-[.17em] text-warning"
                    >
                        Espace vendeur
                    </span>
                </div>


                <h2
                    class="text-2xl xl:text-3xl font-black leading-tight tracking-tight mt-6"
                >
                    Votre boutique est
                    <span class="text-warning">
                        presque prête.
                    </span>
                </h2>

                <p
                    class="text-xs text-success-50/80 leading-relaxed mt-3 max-w-sm"
                >
                    La vérification KYC protège les vendeurs et les acheteurs
                    et contribue à créer une marketplace de confiance au Cameroun.
                </p>

            </div>


            {{-- Timeline --}}
            <div class="relative z-10 my-auto py-8 space-y-3">

                {{-- Etape 1 --}}
                <div
                    class="glass rounded-2xl border border-white/15 p-4 flex items-center gap-3"
                >

                    <div
                        class="w-9 h-9 rounded-xl bg-primary-600 border border-white/20 flex items-center justify-center font-black text-sm shrink-0"
                    >
                        ✓
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-white">
                            1. Dossier soumis
                        </h3>

                        <p class="text-[10px] text-success-100/70 mt-0.5">
                            Vos documents ont été reçus.
                        </p>
                    </div>

                </div>


                {{-- Etape 2 --}}
                <div
                    class="rounded-2xl bg-black/20 backdrop-blur-md border border-warning/40 p-4 flex items-center gap-3 shadow-lg"
                >

                    <div
                        class="w-9 h-9 rounded-xl bg-warning text-slate-900 flex items-center justify-center font-black text-sm shrink-0 soft-pulse"
                    >
                        2
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-warning">
                            2. Vérification
                        </h3>

                        <p class="text-[10px] text-success-100/80 mt-0.5">
                            Notre équipe examine actuellement votre dossier.
                        </p>
                    </div>

                </div>


                {{-- Etape 3 --}}
                <div
                    class="glass rounded-2xl border border-white/10 p-4 flex items-center gap-3 opacity-80"
                >

                    <div
                        class="w-9 h-9 rounded-xl bg-danger border border-white/20 flex items-center justify-center font-black text-sm shrink-0"
                    >
                        3
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-white">
                            3. Activation vendeur
                        </h3>

                        <p class="text-[10px] text-success-100/70 mt-0.5">
                            Votre boutique pourra commencer à vendre.
                        </p>
                    </div>

                </div>

            </div>


            {{-- Bloc confiance --}}
            <div
                class="relative z-10 rounded-2xl bg-white/10 border border-white/10 backdrop-blur-md p-4 mb-4"
            >

                <div class="flex items-start gap-3">

                    <div
                        class="w-9 h-9 rounded-xl bg-warning text-slate-900 flex items-center justify-center shrink-0"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-wider">
                            Vos données sont protégées
                        </h3>

                        <p class="text-[9px] text-success-50/70 mt-1 leading-relaxed">
                            Les informations transmises sont utilisées uniquement
                            dans le cadre de la vérification de votre identité.
                        </p>
                    </div>

                </div>

            </div>


            {{-- Footer droit --}}
            <div
                class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between"
            >

                <div>
                    <p class="text-[9px] text-success-100/60 uppercase tracking-wider font-bold">
                        Assistance
                    </p>

                    <p class="text-[10px] font-black text-warning mt-0.5">
                        support@ali-kamer.cm
                    </p>
                </div>


                <div class="flex items-center gap-1.5">

                    <span
                        class="w-2.5 h-2.5 rounded-full bg-primary-600 border border-white/40"
                    ></span>

                    <span
                        class="w-2.5 h-2.5 rounded-full bg-danger border border-white/40"
                    ></span>

                    <span
                        class="w-2.5 h-2.5 rounded-full bg-warning border border-white/40"
                    ></span>

                </div>

            </div>

        </section>

    </div>

</main>


</body>

</html>
