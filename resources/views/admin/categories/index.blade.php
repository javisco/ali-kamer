@extends('base')
@section('title', 'Gestion des Catégories')

@section('content')
    <!-- Wrapper Alpine.js -->
    <div x-data="{ 
        editModal: false, 
        editCategory: { id: null, name: '', parent_id: '', sort_order: 0, is_active: true } 
    }" class="p-6 bg-gray-50 min-h-screen">
        
        <div class="max-w-7xl mx-auto space-y-4">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">✕</button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Formulaire de création -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Nouvelle Catégorie</h3>

                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nom *</label>
                            <input type="text" name="name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Catégorie Parente</label>
                            <select name="parent_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none">
                                <option value="">Aucune (Catégorie principale)</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Icône / Image</label>
                            <input type="file" name="icon" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-gray-100 file:text-gray-700 file:font-semibold hover:file:bg-gray-200 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Ordre d'affichage</label>
                            <input type="number" name="sort_order" value="0" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 transition outline-none">
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">Activer la catégorie</label>
                        </div>

                        <button type="submit" class="w-full py-3 bg-red-600 text-white font-semibold rounded-xl text-sm hover:bg-red-700 shadow-sm transition">
                            Créer la catégorie
                        </button>
                    </form>
                </div>

                <!-- Arborescence des catégories -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Arborescence des catégories</h3>

                    <div class="divide-y divide-gray-100">
                        @foreach ($categories as $category)
                            <div class="py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if ($category->icon)
                                            <img src="{{ asset('storage/' . $category->icon) }}" class="w-9 h-9 rounded-xl object-cover border border-gray-100">
                                        @else
                                            <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center text-xs font-bold text-gray-400">#</div>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-base">{{ $category->name }}</h4>
                                            <span class="text-xs text-gray-400 font-medium">{{ $category->products_count }} produits</span>
                                        </div>
                                    </div>

                                    <!-- Actions sur la catégorie parente -->
                                    <div class="flex items-center gap-2">
                                        <!-- Bouton Éditer -->
                                        <button @click="
                                            editCategory = {
                                                id: '{{ $category->id }}',
                                                name: '{{ addslashes($category->name) }}',
                                                parent_id: '{{ $category->parent_id }}',
                                                sort_order: '{{ $category->sort_order }}',
                                                is_active: {{ $category->is_active ? 'true' : 'false' }}
                                            };
                                            editModal = true;
                                        " class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <!-- Toggle Status -->
                                        <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 text-xs font-medium rounded-full transition {{ $category->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                                {{ $category->is_active ? 'Actif' : 'Inactif' }}
                                            </button>
                                        </form>

                                        <!-- Supprimer -->
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Sous-catégories -->
                                @if ($category->children->count())
                                    <div class="ml-8 mt-3 space-y-2 border-l-2 border-gray-100 pl-4">
                                        @foreach ($category->children as $child)
                                            <div class="flex items-center justify-between py-1.5 text-sm">
                                                <span class="text-gray-700 font-medium">↳ {{ $child->name }}</span>
                                                
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs text-gray-400">{{ $child->products_count }} produits</span>

                                                    <!-- Modifier enfant -->
                                                    <button @click="
                                                        editCategory = {
                                                            id: '{{ $child->id }}',
                                                            name: '{{ addslashes($child->name) }}',
                                                            parent_id: '{{ $child->parent_id }}',
                                                            sort_order: '{{ $child->sort_order }}',
                                                            is_active: {{ $child->is_active ? 'true' : 'false' }}
                                                        };
                                                        editModal = true;
                                                    " class="text-gray-400 hover:text-indigo-600 transition" title="Modifier">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>

                                                    <!-- Toggle Status Enfant -->
                                                    <form action="{{ route('admin.categories.toggle-status', $child) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="px-2 py-0.5 text-[10px] font-medium rounded-full transition {{ $child->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                                            {{ $child->is_active ? 'Actif' : 'Inactif' }}
                                                        </button>
                                                    </form>

                                                    <!-- Supprimer Enfant -->
                                                    <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Supprimer cette sous-catégorie ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL DE MODIFICATION SÉCURISÉ & ÉLÉGANT -->
        <template x-teleport="body">
            <div x-show="editModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
                 x-cloak>
                
                <div @click.away="editModal = false" 
                     class="w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-900">Modifier la catégorie</h3>
                        <button @click="editModal = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form :action="'{{ url('admin/categories') }}/' + editCategory.id" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nom de la catégorie *</label>
                            <input type="text" name="name" x-model="editCategory.name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Catégorie Parente</label>
                            <select name="parent_id" x-model="editCategory.parent_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition outline-none">
                                <option value="">Aucune (Catégorie principale)</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nouvelle icône (Optionnel)</label>
                            <input type="file" name="icon" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-gray-100 file:text-gray-700 file:font-semibold hover:file:bg-gray-200 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Ordre d'affichage</label>
                            <input type="number" name="sort_order" x-model="editCategory.sort_order" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 transition outline-none">
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editCategory.is_active" class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <label for="edit_is_active" class="text-sm font-medium text-gray-700">Activer la catégorie</label>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" @click="editModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                                Annuler
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 shadow-sm transition">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>
@endsection