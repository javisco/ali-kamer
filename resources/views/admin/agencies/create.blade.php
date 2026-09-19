@extends('layouts.admin')

@section('title', 'Nouvelle agence')

@section('content')

    <div class="min-h-screen bg-slate-50 py-8 sm:py-10">

        <div class="max-w-xl mx-auto px-4 sm:px-6">

            {{-- =====================================================
                 EN-TÊTE
            ====================================================== --}}
            <div class="mb-7">

                <div class="flex items-center gap-2 mb-3">
                    <span class="w-8 h-1 rounded-full bg-primary-600"></span>
                    <span class="w-4 h-1 rounded-full bg-[#FCD116]"></span>
                    <span class="w-3 h-1 rounded-full bg-[#CE1126]"></span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-950">
                    Nouvelle agence
                </h1>

                <p class="mt-1.5 text-sm text-slate-500 font-medium">
                    Ajoutez une nouvelle agence à votre réseau Ali-Kamer.
                </p>

            </div>


            {{-- =====================================================
                 ERREURS
            ====================================================== --}}
            @if ($errors->any())

                <div class="mb-5 rounded-2xl border border-danger-200 bg-danger-50 px-4 py-3.5 shadow-sm">

                    <div class="flex items-start gap-3">

                        <div class="w-8 h-8 shrink-0 rounded-full bg-[#CE1126]/10 text-[#CE1126]
                                    flex items-center justify-center">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z" />
                            </svg>

                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-extrabold text-danger-800 mb-1">
                                Impossible de créer l'agence
                            </p>

                            <div class="space-y-0.5 text-xs font-medium text-danger-700">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 FORMULAIRE
            ====================================================== --}}
            <form method="POST"
                action="{{ route('admin.agencies.store') }}"
                class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

                @csrf

                {{-- Bandeau supérieur --}}
                <div class="h-1.5 bg-gradient-to-r from-primary-600 via-[#FCD116] to-[#CE1126]"></div>

                <div class="p-5 sm:p-7 space-y-6">

                    {{-- =================================================
                         NOM DE L'AGENCE
                    ================================================== --}}
                    <div>

                        <label for="name"
                            class="block text-xs font-extrabold uppercase tracking-wide text-slate-700 mb-2">
                            Nom de l'agence
                            <span class="text-[#CE1126]">*</span>
                        </label>

                        <div class="relative">

                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-slate-400 pointer-events-none">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M12 9h.01M15 9h.01" />
                                </svg>

                            </span>

                            <input id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Ex : General Express"
                                class="w-full h-11 border border-slate-200 bg-slate-50 rounded-xl
                                       pl-10 pr-4 text-sm font-medium text-slate-800
                                       placeholder:text-slate-400 outline-none
                                       focus:bg-white focus:border-primary-600
                                       focus:ring-4 focus:ring-success/10 transition">

                        </div>

                    </div>


                    {{-- =================================================
                         TÉLÉPHONE
                    ================================================== --}}
                    <div>

                        <label for="contact_phone"
                            class="block text-xs font-extrabold uppercase tracking-wide text-slate-700 mb-2">
                            Téléphone contact
                        </label>

                        <div class="relative">

                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-slate-400 pointer-events-none">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M22 16.92v3a2 2 0 01-2.18 2
                                           19.79 19.79 0 01-8.63-3.07
                                           19.5 19.5 0 01-6-6
                                           19.79 19.79 0 01-3.07-8.67
                                           A2 2 0 014.11 2h3a2 2 0 012 1.72
                                           12.84 12.84 0 00.7 2.81
                                           2 2 0 01-.45 2.11L8.09 9.91
                                           a16 16 0 006 6l1.27-1.27
                                           a2 2 0 012.11-.45
                                           12.84 12.84 0 002.81.7
                                           A2 2 0 0122 16.92z" />
                                </svg>

                            </span>

                            <input id="contact_phone"
                                type="tel"
                                name="contact_phone"
                                value="{{ old('contact_phone') }}"
                                placeholder="Ex : 699000000"
                                class="w-full h-11 border border-slate-200 bg-slate-50 rounded-xl
                                       pl-10 pr-4 text-sm font-medium text-slate-800
                                       placeholder:text-slate-400 outline-none
                                       focus:bg-white focus:border-primary-600
                                       focus:ring-4 focus:ring-success/10 transition">

                        </div>

                    </div>


                    {{-- =================================================
                         EMAIL
                    ================================================== --}}
                    <div>

                        <label for="contact_email"
                            class="block text-xs font-extrabold uppercase tracking-wide text-slate-700 mb-2">
                            Email contact
                        </label>

                        <div class="relative">

                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2
                                         text-slate-400 pointer-events-none">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M3 7l9 6 9-6M5 5h14a2 2 0 012 2v10
                                           a2 2 0 01-2 2H5a2 2 0 01-2-2V7
                                           a2 2 0 012-2z" />
                                </svg>

                            </span>

                            <input id="contact_email"
                                type="email"
                                name="contact_email"
                                value="{{ old('contact_email') }}"
                                placeholder="Ex : contact@general.cm"
                                class="w-full h-11 border border-slate-200 bg-slate-50 rounded-xl
                                       pl-10 pr-4 text-sm font-medium text-slate-800
                                       placeholder:text-slate-400 outline-none
                                       focus:bg-white focus:border-primary-600
                                       focus:ring-4 focus:ring-success/10 transition">

                        </div>

                    </div>


                    {{-- =================================================
                         ACTIONS
                    ================================================== --}}
                    <div class="pt-2 border-t border-slate-100">

                        <button type="submit"
                            class="w-full h-11 rounded-xl bg-primary-600 hover:bg-primary-700
                                   text-white text-sm font-extrabold
                                   flex items-center justify-center gap-2
                                   shadow-sm shadow-success-800/10
                                   hover:-translate-y-0.5
                                   transition-all duration-200">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 5v14M5 12h14" />
                            </svg>

                            Créer l'agence

                        </button>

                        <a href="{{ route('admin.agencies.index') }}"
                            class="mt-3 w-full h-10 rounded-xl
                                   flex items-center justify-center
                                   text-xs font-bold text-slate-500
                                   hover:text-primary-600 hover:bg-success-50
                                   transition">

                            ← Annuler et revenir aux agences

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

@endsection