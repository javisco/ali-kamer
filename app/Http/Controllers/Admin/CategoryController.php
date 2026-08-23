<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::parents()
            ->with(['children' => fn($q) => $q->withCount('products')])
            ->withCount('products')
            ->ordered()
            ->paginate(15);

        $parentCategories = Category::parents()->get();

        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'parent_id'   => 'nullable|exists:categories,id',
            'icon'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'is_active'   => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('categories', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'parent_id'   => 'nullable|exists:categories,id|different:id',
            'icon'        => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'is_active'   => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('categories', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return back()->with('success', 'Catégorie mise à jour avec succès.');
    }




    
    // Basculer l'état Actif / Inactif rapide
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return back()->with('success', 'Statut de la catégorie mis à jour.');
    }

    // Suppression sécurisée
    public function destroy(Category $category)
    {
        if ($category->products()->exists() || $category->children()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits ou des sous-catégories.');
        }

        $category->delete();

        return back()->with('success', 'Catégorie supprimée avec succès.');
    }
}