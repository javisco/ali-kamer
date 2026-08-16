<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Tutorial::orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('admin.tutorials.index', compact('tutorials'));
    }

    public function create()
    {
        return view('admin.tutorials.create', [
            'categories' => Tutorial::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'type'             => ['required', 'in:video,text'],
            'role_target'      => ['required', 'in:buyer,seller,all'],
            'category'         => ['required', 'string'],
            'video_url'        => ['nullable', 'url'],
            'thumbnail_url'    => ['nullable', 'url'],
            'content'          => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
        ]);

        $data = $request->all();
        // Si l'admin a cliqué "Publier maintenant"
        $data['is_published'] = $request->input('publish', 0) == 1;

        Tutorial::create($data);

        return redirect()->route('admin.tutorials.index')
            ->with('success', 'Tutoriel ' . ($data['is_published'] ? 'publié' : 'enregistré en brouillon') . '.');
    }




    public function edit(Tutorial $tutorial)
    {
        return view('admin.tutorials.edit', [
            'tutorial'   => $tutorial,
            'categories' => Tutorial::CATEGORIES,
        ]);
    }





    public function update(Request $request, Tutorial $tutorial)
    {
        $request->validate([
            'title'            => ['required', 'string', 'max:200'],
            'type'             => ['required', 'in:video,text'],
            'role_target'      => ['required', 'in:buyer,seller,all'],
            'category'         => ['required', 'string'],
            'video_url'        => ['nullable', 'url'],
            'thumbnail_url'    => ['nullable', 'url'],
            'content'          => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
        ]);

        $tutorial->update($request->all());

        return redirect()->route('admin.tutorials.index')
            ->with('success', 'Tutoriel mis à jour.');
    }

    public function toggle(Tutorial $tutorial)
    {
        $tutorial->update(['is_published' => ! $tutorial->is_published]);

        $action = $tutorial->fresh()->is_published ? 'publié' : 'dépublié';
        return back()->with('success', "Tutoriel {$action}.");
    }

    public function destroy(Tutorial $tutorial)
    {
        $tutorial->delete();
        return back()->with('success', 'Tutoriel supprimé.');
    }
}
