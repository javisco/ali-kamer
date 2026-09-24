<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use App\Services\KycService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KycAdminController extends Controller
{
    public function __construct(protected KycService $kycService)
    {
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $dossiers = KycDocument::with('user')
            ->where('status', $status)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending' => KycDocument::where('status', 'pending')->count(),
            'reviewing' => KycDocument::where('status', 'reviewing')->count(),
            'approved' => KycDocument::where('status', 'approved')->count(),
            'rejected' => KycDocument::where('status', 'rejected')->count(),
        ];

        return view('admin.kyc.index', compact('dossiers', 'counts', 'status'));
    }

    public function show(KycDocument $kyc): View
    {
        if ($kyc->isPending()) {
            $kyc->update(['status' => 'reviewing']);
        }

        return view('admin.kyc.show', compact('kyc'));
    }

    public function approve(KycDocument $kyc)
    {
        $this->kycService->approve($kyc, Auth::user());

        return redirect()->route('admin.kyc.index', ['status' => 'approved'])
            ->with('success', "{$kyc->user->name} approuvé. Boutique activée.");
    }

    public function reject(Request $request, KycDocument $kyc)
    {
        $request->validate(['reason' => 'required|string|min:10|max:2000']);

        $this->kycService->reject($kyc, Auth::user(), $request->reason);

        return redirect()->route('admin.kyc.index', ['status' => 'rejected'])
            ->with('success', 'Dossier rejeté.');
    }
}
