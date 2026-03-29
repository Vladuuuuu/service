<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Invoice;
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
            ->with(['car', 'service', 'invoice'])
            ->latest()
            ->paginate(20);

        return view('client.interventions', compact('interventions'));
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
