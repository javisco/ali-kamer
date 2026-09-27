<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sanction;
use App\Models\SanctionRestriction;
use App\Models\User;
use App\Services\SanctionEngine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SanctionsAdminController extends Controller
{
    public function __construct(
        protected SanctionEngine $sanctionEngine
    ) {
    }

    /**
     * Affiche la liste des sanctions avec filtres et statistiques.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'active');
        $type = $request->get('type', 'all');
        $search = $request->get('search');

        $query = Sanction::query()
            ->with(['user', 'suspension', 'restrictions', 'creator', 'lifter'])
            ->latest('starts_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($type !== 'all') {
            $query->where('type', strtoupper($type));
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sanctions = $query->paginate(20)->withQueryString();

        $counts = [
            'active_suspensions'  => Sanction::where('type', Sanction::TYPE_SUSPENSION)->where('status', Sanction::STATUS_ACTIVE)->count(),
            'active_bans'         => Sanction::where('type', Sanction::TYPE_BAN)->where('status', Sanction::STATUS_ACTIVE)->count(),
            'active_restrictions' => Sanction::where('type', Sanction::TYPE_RESTRICTION)->where('status', Sanction::STATUS_ACTIVE)->count(),
            'total_lifted'        => Sanction::where('status', Sanction::STATUS_LIFTED)->count(),
            'all'                 => Sanction::count(),
        ];

        return view('admin.sanctions.index', compact('sanctions', 'counts', 'status', 'type', 'search'));
    }

    /**
     * Applique une nouvelle sanction (suspension, bannissement ou restriction).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'                 => ['required', 'exists:users,id'],
            'type'                    => ['required', 'in:SUSPENSION,RESTRICTION,BAN'],
            'reason'                  => ['required', 'string', 'min:5', 'max:1000'],
            'reason_code'             => ['nullable', 'string', 'max:80'],
            'duration'                => ['required_if:type,SUSPENSION', 'nullable', 'in:24h,3d,7d,14d,30d,custom'],
            'custom_duration_minutes' => ['nullable', 'integer', 'min:1', 'max:525600'],
            'restrictions'            => ['nullable', 'array'],
            'restrictions.*'          => ['string', 'in:cannot_buy,cannot_sell,cannot_publish,cannot_withdraw,cannot_message,cannot_create_order'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $reasonCode = $validated['reason_code'] ?: 'admin_manual_sanction';
        $restrictions = $validated['restrictions'] ?? [];
        $admin = Auth::user();

        if ($validated['type'] === Sanction::TYPE_SUSPENSION) {
            $minutes = match ($validated['duration']) {
                '24h'    => 24 * 60,
                '3d'     => 3 * 24 * 60,
                '7d'     => 7 * 24 * 60,
                '14d'    => 14 * 24 * 60,
                '30d'    => 30 * 24 * 60,
                'custom' => (int) ($validated['custom_duration_minutes'] ?? 60),
                default  => 24 * 60,
            };

            $this->sanctionEngine->suspend(
                user: $user,
                durationMinutes: $minutes,
                reason: $validated['reason'],
                reasonCode: $reasonCode,
                restrictions: $restrictions,
                createdBy: $admin
            );

            return redirect()->route('admin.sanctions.index')
                ->with('success', "Suspension temporaire de {$user->name} appliquée avec succès.");
        }

        if ($validated['type'] === Sanction::TYPE_BAN) {
            $this->sanctionEngine->ban(
                user: $user,
                reason: $validated['reason'],
                reasonCode: $reasonCode,
                createdBy: $admin
            );

            return redirect()->route('admin.sanctions.index')
                ->with('success', "Bannissement définitif de {$user->name} appliqué avec succès.");
        }

        if ($validated['type'] === Sanction::TYPE_RESTRICTION) {
            if (empty($restrictions)) {
                return back()->withErrors(['restrictions' => 'Veuillez sélectionner au moins une restriction.']);
            }

            $this->sanctionEngine->restrict(
                user: $user,
                restrictions: $restrictions,
                reason: $validated['reason'],
                reasonCode: $reasonCode,
                createdBy: $admin
            );

            return redirect()->route('admin.sanctions.index')
                ->with('success', "Restrictions appliquées au compte {$user->name} avec succès.");
        }

        return redirect()->route('admin.sanctions.index');
    }

    /**
     * Lève manuellement une sanction active.
     */
    public function lift(Request $request, Sanction $sanction): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        if (! $sanction->isActive()) {
            return back()->withErrors(['error' => 'Cette sanction n\'est plus active.']);
        }

        $this->sanctionEngine->liftSanction(
            sanction: $sanction,
            admin: Auth::user(),
            reason: $request->input('reason')
        );

        return redirect()->route('admin.sanctions.index')
            ->with('success', "La sanction #{$sanction->id} de {$sanction->user->name} a été levée avec succès.");
    }
}
