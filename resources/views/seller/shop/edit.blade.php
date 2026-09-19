@extends('layouts.seller')
@section('title', 'Modifier ma boutique - Ali-Kamer')

@section('content')
    <div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6">
        <div class="max-w-2xl mx-auto space-y-0">

            <!-- 1. EN-TÊTE BANNIÈRE ALI-KAMER -->
            <div class="bg-primary-600 rounded-t-3xl p-6 text-center shadow-xs relative overflow-hidden">
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
                
                <span class="inline-block bg-accent-500 text-slate-900 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full mb-2">
                    Espace Vendeur
                </span>
                <h1 class="text-2xl font-black text-white tracking-wider uppercase">
                    ALI-KAMER
                </h1>
                <p class="text-white/80 text-sm font-medium mt-0.5">
                    Modifier les informations de ma boutique
                </p>
            </div>

            <!-- 2. CORPS DU FORMULAIRE -->
            <div class="bg-white rounded-b-3xl p-6 sm:p-8 shadow-xs border-x border-b border-slate-200">

                {{-- Alertes de Succès --}}
                @if (session('success'))
                    <div class="bg-primary-600/10 border border-primary-600/20 text-primary-600 px-4 py-3 rounded-xl mb-5 text-sm font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Alertes d'Erreurs --}}
                @if ($errors->any())
                    <div class="bg-danger/10 border border-danger/20 text-danger px-4 py-3 rounded-xl mb-5">
                        <ul class="list-disc list-inside space-y-1 text-xs font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('seller.shop.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- LIGNE 1 : Nom de la boutique + Ville -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-900 mb-1.5">
                                Nom de la boutique <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $shop->name) }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 transition duration-200 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 hover:border-slate-300 shadow-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-900 mb-1.5">
                                Ville <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="city" value="{{ old('city', $shop->city) }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 transition duration-200 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 hover:border-slate-300 shadow-xs">
                        </div>
                    </div>

                    <!-- LIGNE 2 : Téléphone + Adresse -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-900 mb-1.5">
                                Téléphone <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" required
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 transition duration-200 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 hover:border-slate-300 shadow-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-900 mb-1.5">
                                Adresse physique
                            </label>
                            <input type="text" name="address" value="{{ old('address', $shop->address) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 transition duration-200 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 hover:border-slate-300 shadow-xs">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-900 mb-1.5">
                            Description de la boutique
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 transition duration-200 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-500/20 hover:border-slate-300 shadow-xs">{{ old('description', $shop->description) }}</textarea>
                    </div>

                    <!-- Logo (Aperçu + Input) -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-900 mb-1.5">
                            Logo de la boutique
                        </label>
                        
                        <div class="flex items-center gap-4">
                            @if(isset($shop->logo) && $shop->logo)
                                <img src="{{ Storage::url($shop->logo) }}" alt="Logo boutique" 
                                    class="w-14 h-14 rounded-xl object-cover border border-slate-200 shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <input type="file" name="logo" accept="image/*"
                                class="w-full border border-slate-200 rounded-xl p-2 text-xs font-semibold text-slate-900 transition duration-200 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-primary-600/10 file:text-primary-600 hover:file:bg-primary-600 hover:file:text-white cursor-pointer shadow-xs">
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3">
                        <a href="{{ url()->previous() }}" 
                            class="sm:col-span-1 w-full border border-slate-200 text-slate-900 font-bold py-3 px-4 rounded-xl hover:bg-slate-50 active:scale-98 transition text-sm text-center shadow-xs block">
                            Retour
                        </a>
                        
                        <button type="submit" 
                            class="sm:col-span-2 w-full bg-accent-500 hover:bg-accent-600 text-slate-900 font-black py-3 px-4 rounded-xl active:scale-98 transition text-sm tracking-wide shadow-xs">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection