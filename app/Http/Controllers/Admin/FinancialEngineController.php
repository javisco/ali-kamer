<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;


class FinancialEngineController extends Controller
{
    public function index()
    {
        // Grouper les paramètres par groupe
        $settings = PlatformSetting::orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('group');

        // Simuler une transaction pour visualiser l'impact des frais
        $simulation = $this->simulate(10000);

        return view('admin.financial-engine.index', compact('settings', 'simulation'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings'   => ['required', 'array'],
            'settings.*' => ['required'],
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = PlatformSetting::where('key', $key)->first();

            if (! $setting) continue;

            // Validation selon le type
            if ($setting->type === 'percentage') {
                // Les pourcentages doivent être entre 0 et 100
                if ($value < 0 || $value > 100) {
                    return back()->withErrors([$key => "Le pourcentage doit être entre 0 et 100."]);
                }
            }

            if ($setting->type === 'integer' && $value < 0) {
                return back()->withErrors([$key => "La valeur doit être positive."]);
            }

            PlatformSetting::setValue($key, $value);
        }

        return back()->with('success', 'Paramètres mis à jour. Les nouvelles commandes utiliseront ces taux.');
    }

    // Simuler une transaction pour visualiser l'impact
    private function simulate(int $amount): array
    {
        $protectionRate     = PlatformSetting::getRate('protection_rate');
        $gatewayRate        = PlatformSetting::getRate('gateway_collect_rate');
        $commissionRate     = PlatformSetting::getRate('platform_commission_rate');
        $agencyRate         = PlatformSetting::getRate('agency_commission_rate');
        $payoutRate         = PlatformSetting::getRate('gateway_payout_rate');

        $protection         = (int) round($amount * $protectionRate);
        $gateway            = (int) round($amount * $gatewayRate);
        $totalBuyer         = $amount + $protection + $gateway;
        $commission         = (int) round($amount * $commissionRate);
        $agencyCommission   = (int) round($amount * $agencyRate);
        $payoutFee          = (int) round($amount * $payoutRate);
        $netSeller          = $amount - $commission - $agencyCommission - $payoutFee;
        $platformProfit     = $protection + $commission - $agencyCommission;

        return compact(
            'amount',
            'protection',
            'gateway',
            'totalBuyer',
            'commission',
            'agencyCommission',
            'payoutFee',
            'netSeller',
            'platformProfit'
        );
    }
}
