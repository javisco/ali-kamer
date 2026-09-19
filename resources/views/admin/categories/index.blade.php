
@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@section('content')

    {{-- =========================================================
        GESTION DES CATÉGORIES — STYLE ALI-KAMER
    ========================================================== --}}

    <div x-data="{
        editModal: false,
        editCategory: {
            id: null,
            name: '',
            parent_id: '',
            sort_order: 0,
            is_active: true
        }
    }"
    class="min-h-screen bg-slate-50 p-4 sm:p-5 lg:p-6">

        <div class="max-w-7xl mx-auto space-y-5">

            {{-- =====================================================
                EN-TÊTE
            ====================================================== --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 rounded-full bg-primary-600"></span>

                        <span class="text-[10px] font-black uppercase tracking-[0.16em] text-primary-600">
                            Administration
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Gestion des catégories
                    </h1>

                    <p class="mt-1 text-xs sm:text-sm text-slate-500">
                        Organisez les catégories et sous-catégories de la marketplace.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 self-start sm:self-auto
                            px-3 py-2 rounded-xl
                            bg-white border border-slate-200
                            shadow-sm">

                    <span class="w-2 h-2 rounded-full bg-accent-500"></span>

                    <span class="text-xs font-bold text-slate-600">
                        {{ $categories->total() }} catégories
                    </span>
                </div>

            </div>


            {{-- =====================================================
                FLASH MESSAGES
            ====================================================== --}}
            @if(session('success'))
                <div class="p-3.5 bg-primary-600/5 border border-primary-600/20
                            text-primary-600 rounded-xl text-xs sm:text-sm font-semibold
                            flex items-center justify-between gap-3">

                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-primary-600/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>

                        <span>{{ session('success') }}</span>
                    </div>

                    <button onclick="this.parentElement.remove()"
                            class="text-primary-600/60 hover:text-primary-600 transition">
                        ✕
                    </button>
                </div>
            @endif


            @if(session('error'))
                <div class="p-3.5 bg-danger/5 border border-danger/20
                            text-danger rounded-xl text-xs sm:text-sm font-semibold
                            flex items-center justify-between gap-3">

                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-danger/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 8v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 2.64h15.58a2 2 0 001.74-2.64l-7.82-14a2 2 0 00-3.42 0z"/>
                            </svg>
                        </span>

                        <span>{{ session('error') }}</span>
                    </div>

                    <button onclick="this.parentElement.remove()"
                            class="text-danger/60 hover:text-danger transition">
                        ✕
                    </button>
                </div>
            @endif


            {{-- =====================================================
                CONTENU PRINCIPAL
            ====================================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">


                {{-- =================================================
                    FORMULAIRE DE CRÉATION
                ================================================== --}}
                <div class="bg-white rounded-2xl border border-slate-200
                            shadow-sm overflow-hidden h-fit">

                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-slate-100
                                bg-slate-900 text-white">

                        <div class="flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl
                                        bg-accent-500/15
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-accent-500"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-sm font-black">
                                    Nouvelle catégorie
                                </h3>

                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    Ajouter une nouvelle rubrique
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- Form --}}
                    <form action="{{ route('admin.categories.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="p-5 space-y-4">

                        @csrf

                        {{-- Nom --}}
                        <div>
                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Nom *
                            </label>

                            <input type="text"
                                   name="name"
                                   required
                                   placeholder="Ex. Électronique"
                                   class="w-full px-3.5 py-2.5
                                          bg-slate-50 border border-slate-200
                                          rounded-xl text-sm text-slate-800
                                          placeholder:text-slate-400
                                          focus:bg-white
                                          focus:ring-2 focus:ring-primary-500/15
                                          focus:border-primary-600
                                          transition-all outline-none">
                        </div>


                        {{-- Parent --}}
                        <div>
                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Catégorie parente
                            </label>

                            <select name="parent_id"
                                    class="w-full px-3.5 py-2.5
                                           bg-slate-50 border border-slate-200
                                           rounded-xl text-sm text-slate-800
                                           focus:bg-white
                                           focus:ring-2 focus:ring-primary-500/15
                                           focus:border-primary-600
                                           transition-all outline-none">

                                <option value="">
                                    Aucune (Catégorie principale)
                                </option>

                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">
                                        {{ $parent->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>


                        {{-- Icone --}}
                        <div>
                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Icône / Image
                            </label>

                            <input type="file"
                                   name="icon"
                                   class="w-full text-xs text-slate-500
                                          file:mr-3
                                          file:py-2
                                          file:px-3
                                          file:rounded-lg
                                          file:border-0
                                          file:bg-primary-600/10
                                          file:text-primary-600
                                          file:font-bold
                                          hover:file:bg-primary-600/15
                                          transition">
                        </div>


                        {{-- Ordre --}}
                        <div>
                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Ordre d'affichage
                            </label>

                            <input type="number"
                                   name="sort_order"
                                   value="0"
                                   class="w-full px-3.5 py-2.5
                                          bg-slate-50 border border-slate-200
                                          rounded-xl text-sm
                                          focus:bg-white
                                          focus:ring-2 focus:ring-primary-500/15
                                          focus:border-primary-600
                                          transition outline-none">
                        </div>


                        {{-- Statut --}}
                        <div class="flex items-center gap-2 pt-1">

                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   checked
                                   class="w-4 h-4 rounded
                                          border-slate-300
                                          text-primary-600
                                          focus:ring-primary-500">

                            <label for="is_active"
                                   class="text-xs font-bold text-slate-700">
                                Activer la catégorie
                            </label>

                        </div>


                        {{-- Bouton --}}
                        <button type="submit"
                                class="w-full py-2.5
                                       bg-primary-600
                                       text-white
                                       font-black
                                       rounded-xl
                                       text-xs
                                       hover:bg-primary-700
                                       active:scale-[0.99]
                                       shadow-sm
                                       transition">

                            <span class="inline-flex items-center justify-center gap-2">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 4v16m8-8H4"/>
                                </svg>

                                Créer la catégorie

                            </span>
                        </button>

                    </form>
                </div>


                {{-- =================================================
                    ARBORESCENCE
                ================================================== --}}
                <div class="lg:col-span-2 bg-white rounded-2xl
                            border border-slate-200 shadow-sm overflow-hidden">

                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-slate-100
                                flex items-center justify-between gap-3">

                        <div class="flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl bg-primary-600/10
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-primary-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M3 7h18M3 12h18M3 17h18"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-sm font-black text-slate-900">
                                    Arborescence des catégories
                                </h3>

                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    Catégories principales et sous-catégories
                                </p>
                            </div>

                        </div>

                        <span class="hidden sm:inline-flex items-center
                                     px-2.5 py-1 rounded-lg
                                     bg-accent-500/10
                                     text-[#9a6200]
                                     border border-accent-500/20
                                     text-[10px] font-black">
                            Organisation
                        </span>

                    </div>


                    {{-- Liste --}}
                    <div class="divide-y divide-slate-100">

                        @foreach ($categories as $category)

                            <div class="px-5 py-4 hover:bg-slate-50/70 transition">


                                {{-- =============================
                                    CATÉGORIE PARENTE
                                ============================== --}}
                                <div class="flex items-center justify-between gap-3">

                                    <div class="flex items-center gap-3 min-w-0">

                                        @if ($category->icon)

                                            <img src="{{ asset('storage/' . $category->icon) }}"
                                                 class="w-10 h-10 rounded-xl object-cover
                                                        border border-slate-200 shadow-sm">

                                        @else

                                            <div class="w-10 h-10 bg-primary-600/10
                                                        rounded-xl flex items-center justify-center
                                                        text-xs font-black text-primary-600">
                                                #
                                            </div>

                                        @endif


                                        <div class="min-w-0">

                                            <h4 class="font-black text-slate-900
                                                       text-sm sm:text-base truncate">
                                                {{ $category->name }}
                                            </h4>

                                            <span class="inline-flex items-center gap-1
                                                         text-[10px] text-slate-400
                                                         font-bold mt-0.5">

                                                <span class="w-1.5 h-1.5 rounded-full
                                                             bg-accent-500"></span>

                                                {{ $category->products_count }} produits

                                            </span>

                                        </div>

                                    </div>


                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1.5 shrink-0">

                                        {{-- Modifier --}}
                                        <button
                                            @click="
                                                editCategory = {
                                                    id: '{{ $category->id }}',
                                                    name: '{{ addslashes($category->name) }}',
                                                    parent_id: '{{ $category->parent_id }}',
                                                    sort_order: '{{ $category->sort_order }}',
                                                    is_active: {{ $category->is_active ? 'true' : 'false' }}
                                                };
                                                editModal = true;
                                            "
                                            class="p-2 text-slate-400
                                                   hover:text-primary-600
                                                   hover:bg-primary-600/10
                                                   rounded-lg transition"
                                            title="Modifier">

                                            <svg class="w-4 h-4"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>

                                        </button>


                                        {{-- Statut --}}
                                        <form action="{{ route('admin.categories.toggle-status', $category) }}"
                                              method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="px-2.5 py-1 text-[10px]
                                                           font-black rounded-full transition
                                                           {{ $category->is_active
                                                               ? 'bg-primary-600/10 text-primary-600 border border-primary-600/20'
                                                               : 'bg-slate-100 text-slate-500 border border-slate-200' }}">

                                                {{ $category->is_active ? 'Actif' : 'Inactif' }}

                                            </button>
                                        </form>


                                        {{-- Supprimer --}}
                                        <form action="{{ route('admin.categories.destroy', $category) }}"
                                              method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="p-2 text-slate-400
                                                           hover:text-danger
                                                           hover:bg-danger/10
                                                           rounded-lg transition"
                                                    title="Supprimer">

                                                <svg class="w-4 h-4"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </div>


                                {{-- =============================
                                    SOUS-CATÉGORIES
                                ============================== --}}
                                @if ($category->children->count())

                                    <div class="ml-5 sm:ml-8 mt-3
                                                space-y-1.5
                                                border-l-2 border-accent-500/30
                                                pl-4">

                                        @foreach ($category->children as $child)

                                            <div class="flex items-center justify-between
                                                        gap-3 py-2 px-2 rounded-lg
                                                        hover:bg-accent-500/5 transition">

                                                <div class="flex items-center gap-2 min-w-0">

                                                    <span class="text-accent-500 text-xs">
                                                        ↳
                                                    </span>

                                                    <span class="text-xs sm:text-sm
                                                                 text-slate-700
                                                                 font-bold truncate">
                                                        {{ $child->name }}
                                                    </span>

                                                </div>


                                                <div class="flex items-center gap-2 shrink-0">

                                                    <span class="hidden sm:inline text-[10px]
                                                                 text-slate-400 font-bold">
                                                        {{ $child->products_count }} produits
                                                    </span>


                                                    {{-- Modifier --}}
                                                    <button
                                                        @click="
                                                            editCategory = {
                                                                id: '{{ $child->id }}',
                                                                name: '{{ addslashes($child->name) }}',
                                                                parent_id: '{{ $child->parent_id }}',
                                                                sort_order: '{{ $child->sort_order }}',
                                                                is_active: {{ $child->is_active ? 'true' : 'false' }}
                                                            };
                                                            editModal = true;
                                                        "
                                                        class="p-1.5 text-slate-400
                                                               hover:text-primary-600
                                                               hover:bg-primary-600/10
                                                               rounded-lg transition"
                                                        title="Modifier">

                                                        <svg class="w-3.5 h-3.5"
                                                             fill="none"
                                                             stroke="currentColor"
                                                             viewBox="0 0 24 24">

                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>

                                                    </button>


                                                    {{-- Statut --}}
                                                    <form action="{{ route('admin.categories.toggle-status', $child) }}"
                                                          method="POST">

                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                                class="px-2 py-0.5 text-[9px]
                                                                       font-black rounded-full transition
                                                                       {{ $child->is_active
                                                                           ? 'bg-primary-600/10 text-primary-600 border border-primary-600/15'
                                                                           : 'bg-slate-100 text-slate-500' }}">

                                                            {{ $child->is_active ? 'Actif' : 'Inactif' }}

                                                        </button>

                                                    </form>


                                                    {{-- Supprimer --}}
                                                    <form action="{{ route('admin.categories.destroy', $child) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Supprimer cette sous-catégorie ?');">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="p-1.5 text-slate-400
                                                                       hover:text-danger
                                                                       hover:bg-danger/10
                                                                       rounded-lg transition">

                                                            <svg class="w-3.5 h-3.5"
                                                                 fill="none"
                                                                 stroke="currentColor"
                                                                 viewBox="0 0 24 24">

                                                                <path stroke-linecap="round"
                                                                      stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>

                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                @endif

                            </div>

                        @endforeach

                    </div>


                    {{-- Pagination --}}
                    <div class="px-5 py-4 border-t border-slate-100">
                        {{ $categories->links() }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            MODAL DE MODIFICATION
        ========================================================== --}}
        <template x-teleport="body">

            <div x-show="editModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[999]
                        flex items-center justify-center
                        p-4 bg-slate-900/70 backdrop-blur-sm"
                 x-cloak>

                <div @click.away="editModal = false"
                     class="w-full max-w-lg
                            bg-white rounded-2xl
                            shadow-2xl
                            border border-slate-200
                            overflow-hidden">


                    {{-- Modal Header --}}
                    <div class="px-5 py-4
                                border-b border-slate-100
                                flex items-center justify-between
                                bg-slate-900">

                        <div class="flex items-center gap-3">

                            <div class="w-9 h-9 rounded-xl
                                        bg-accent-500/15
                                        flex items-center justify-center">

                                <svg class="w-5 h-5 text-accent-500"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>

                            </div>

                            <div>
                                <h3 class="text-sm font-black text-white">
                                    Modifier la catégorie
                                </h3>

                                <p class="text-[10px] text-slate-400">
                                    Modifier les informations
                                </p>
                            </div>

                        </div>


                        <button @click="editModal = false"
                                class="text-slate-400 hover:text-white
                                       p-1.5 rounded-lg
                                       hover:bg-white/10 transition">

                            <svg class="w-5 h-5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>

                            </svg>

                        </button>

                    </div>


                    {{-- Modal Body --}}
                    <form :action="'{{ url('admin/categories') }}/' + editCategory.id"
                          method="POST"
                          enctype="multipart/form-data"
                          class="p-5 space-y-4">

                        @csrf
                        @method('PUT')


                        {{-- Nom --}}
                        <div>

                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Nom de la catégorie *
                            </label>

                            <input type="text"
                                   name="name"
                                   x-model="editCategory.name"
                                   required
                                   class="w-full px-3.5 py-2.5
                                          bg-slate-50
                                          border border-slate-200
                                          rounded-xl text-sm
                                          focus:bg-white
                                          focus:ring-2
                                          focus:ring-primary-500/15
                                          focus:border-primary-600
                                          transition outline-none">

                        </div>


                        {{-- Parent --}}
                        <div>

                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Catégorie parente
                            </label>

                            <select name="parent_id"
                                    x-model="editCategory.parent_id"
                                    class="w-full px-3.5 py-2.5
                                           bg-slate-50
                                           border border-slate-200
                                           rounded-xl text-sm
                                           focus:bg-white
                                           focus:ring-2
                                           focus:ring-primary-500/15
                                           focus:border-primary-600
                                           transition outline-none">

                                <option value="">
                                    Aucune (Catégorie principale)
                                </option>

                                @foreach ($parentCategories as $parent)

                                    <option value="{{ $parent->id }}">
                                        {{ $parent->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Icone --}}
                        <div>

                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Nouvelle icône (Optionnel)
                            </label>

                            <input type="file"
                                   name="icon"
                                   class="w-full text-xs text-slate-500
                                          file:mr-3
                                          file:py-2
                                          file:px-3
                                          file:rounded-lg
                                          file:border-0
                                          file:bg-primary-600/10
                                          file:text-primary-600
                                          file:font-bold
                                          hover:file:bg-primary-600/15
                                          transition">

                        </div>


                        {{-- Ordre --}}
                        <div>

                            <label class="block text-[10px] font-black
                                          text-slate-500 uppercase tracking-wider mb-1.5">
                                Ordre d'affichage
                            </label>

                            <input type="number"
                                   name="sort_order"
                                   x-model="editCategory.sort_order"
                                   class="w-full px-3.5 py-2.5
                                          bg-slate-50
                                          border border-slate-200
                                          rounded-xl text-sm
                                          focus:bg-white
                                          focus:ring-2
                                          focus:ring-primary-500/15
                                          focus:border-primary-600
                                          transition outline-none">

                        </div>


                        {{-- Statut --}}
                        <div class="flex items-center gap-2 pt-1">

                            <input type="checkbox"
                                   name="is_active"
                                   id="edit_is_active"
                                   value="1"
                                   :checked="editCategory.is_active"
                                   class="w-4 h-4 rounded
                                          border-slate-300
                                          text-primary-600
                                          focus:ring-primary-500">

                            <label for="edit_is_active"
                                   class="text-xs font-bold text-slate-700">
                                Activer la catégorie
                            </label>

                        </div>


                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-2
                                    pt-4 border-t border-slate-100">

                            <button type="button"
                                    @click="editModal = false"
                                    class="px-4 py-2.5
                                           bg-slate-100
                                           text-slate-700
                                           rounded-xl
                                           text-xs font-black
                                           hover:bg-slate-200
                                           transition">

                                Annuler

                            </button>

                            <button type="submit"
                                    class="px-5 py-2.5
                                           bg-primary-600
                                           text-white
                                           rounded-xl
                                           text-xs font-black
                                           hover:bg-primary-700
                                           shadow-sm
                                           transition">

                                <span class="inline-flex items-center gap-2">

                                    <svg class="w-4 h-4"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M5 13l4 4L19 7"/>

                                    </svg>

                                    Enregistrer

                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </template>

    </div>

@endsection

