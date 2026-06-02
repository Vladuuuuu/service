<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $carIds = $user->cars()->pluck('id');

        $cars = $user->cars()
            ->with(['interventions' => fn($q) => $q->latest()->limit(1), 'interventions.service'])
            ->withCount('interventions')
            ->get();

        $activeInterventions = Intervention::whereIn('car_id', $carIds)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['car', 'service'])
            ->latest()
            ->get();

        $recentInterventions = Intervention::whereIn('car_id', $carIds)
            ->with(['car', 'service'])
            ->latest()
            ->take(8)
            ->get();

        $totalSpent = Intervention::whereIn('car_id', $carIds)
            ->where('status', 'completed')
            ->sum('final_cost');

        $invoiceCount = Invoice::whereHas('intervention', fn($q) => $q->whereIn('car_id', $carIds))->count();

        return view('client.dashboard', compact(
            'user', 'cars', 'activeInterventions', 'recentInterventions', 'totalSpent', 'invoiceCount'
        ));
    }

    public function interventions(Request $request): View
    {
        $carIds = $request->user()->cars()->pluck('id');

        $interventions = Intervention::whereIn('car_id', $carIds)
            ->with(['car', 'service', 'invoice', 'parts'])
            ->latest()
            ->paginate(20);

        return view('client.interventions', compact('interventions'));
    }

    public function approveDeviz(Request $request, Intervention $intervention): RedirectResponse
    {
        $this->authorizeDevizAction($request->user(), $intervention);

        $intervention->update(['deviz_status' => 'aprobat']);

        return back()->with('success', 'Deviz aprobat. Serviceul va continua lucrarea.');
    }

    public function rejectDeviz(Request $request, Intervention $intervention): RedirectResponse
    {
        $this->authorizeDevizAction($request->user(), $intervention);
        abort_if($intervention->invoice()->exists(), 409, 'Există deja o factură pentru această intervenție.');

        $intervention->update([
            'deviz_status' => 'respins',
            'status' => 'cancelled',
            'completed_at' => now(),
        ]);

        // Facturăm doar manopera — piesele nu au fost montate
        $lastNumber = Invoice::max('id') + 1;

        Invoice::create([
            'intervention_id' => $intervention->id,
            'number' => 'FA-' . str_pad($lastNumber, 5, '0', STR_PAD_LEFT),
            'total' => $intervention->deviz_manopera ?? 0,
            'issued_at' => now(),
        ]);

        return back()->with('success', 'Deviz respins. S-a emis factură pentru manopera efectuată.');
    }

    private function authorizeDevizAction($user, Intervention $intervention): void
    {
        abort_unless($intervention->car->user_id === $user->id, 403);
        abort_unless($intervention->deviz_status === 'trimis', 422, 'Nu există un deviz în așteptare pentru această intervenție.');
    }

    public function invoices(Request $request): View
    {
        $carIds = $request->user()->cars()->pluck('id');

        $invoices = Invoice::whereHas('intervention', fn($q) => $q->whereIn('car_id', $carIds))
            ->with(['intervention.car', 'intervention.service'])
            ->latest('issued_at')
            ->paginate(20);

        return view('client.invoices', compact('invoices'));
    }
}
