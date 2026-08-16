<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;

class TutorialController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        // Regrouper les tutoriels par catégorie pour ce rôle
        $tutorials = Tutorial::published()
            ->forRole($role)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('tutorials.index', compact('tutorials'));
    }

    public function show(Tutorial $tutorial)
    {
        abort_unless($tutorial->is_published, 404);

        // Tutoriels similaires dans la même catégorie
        $related = Tutorial::published()
            ->byCategory($tutorial->category)
            ->where('id', '!=', $tutorial->id)
            ->limit(4)
            ->get();

        return view('tutorials.show', compact('tutorial', 'related'));
    }
}
