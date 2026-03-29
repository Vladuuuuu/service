<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicePanelController extends Controller
{
    private function getService(Request $request)
    {
        $service = $request->user()->service;
        abort_unless($service, 403, 'Nu ai un service asociat.');
        return $service;
    }

    public function dashboard(Request $request): View
    {
        $service = $this->getService($request);

        $pendingCount = $service->interventions()->where('status', 'pending')->count();
        $inProgressCount = $service->interventions()->where('status', 'in_progress')->count();
        $completedCount = $service->interventions()->where('status', 'completed')->count();
        $totalRevenue = $service->interventions()->where('status', 'completed')->sum('final_cost');

        $pendingInterventions = $service->interventions()
            ->where('status', 'pending')
            ->with(['car.user'])
            ->latest('scheduled_at')
            ->get();

        $activeInterventions = $service->interventions()
            ->where('status', 'in_progress')
            ->with(['car.user'])
            ->latest()
            ->get();

        return view('service.dashboard', compact(
            'service', 'pendingCount', 'inProgressCount', 'completedCount',
            'totalRevenue', 'pendingInterventions', 'activeInterventions'
        ));
    }

    public function interventions(Request $request): View
    {
        $service = $this->getService($request);

        $query = $service->interventions()->with(['car.user', 'invoice']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $interventions = $query->latest()->paginate(20);

        return view('service.interventions', compact('interventions'));
    }

    public function showIntervention(Request $request, Intervention $intervention): View
    {
        $service = $this->getService($request);
        abort_unless($intervention->service_id === $service->id, 403);

        $intervention->load(['car.user', 'invoice']);

        return view('service.intervention-show', compact('intervention'));
    }

    public function updateStatus(Request $request, Intervention $intervention): RedirectResponse
    {
        $service = $this->getService($request);
        abort_unless($intervention->service_id === $service->id, 403);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $data = ['status' => $validated['status']];

        if ($validated['status'] === 'completed') {
            $data['completed_at'] = now();
        }

        $intervention->update($data);

        return back()->with('success', 'Status actualizat.');
    }

    public function updateIntervention(Request $request, Intervention $intervention): RedirectResponse
    {
        $service = $this->getService($request);
        abort_unless($intervention->service_id === $service->id, 403);

        $validated = $request->validate([
            'estimated_hours' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'km_at_intervention' => 'nullable|integer|min:0',
            'description' => 'required|string|max:1000',
        ]);

        $intervention->update($validated);

        // Update car km if provided and higher
        if (!empty($validated['km_at_intervention']) && $validated['km_at_intervention'] > $intervention->car->km_current) {
            $intervention->car->update(['km_current' => $validated['km_at_intervention']]);
        }

        return back()->with('success', 'Intervenție actualizată.');
    }

    public function createInvoice(Request $request, Intervention $intervention): RedirectResponse
    {
        $service = $this->getService($request);
        abort_unless($intervention->service_id === $service->id, 403);
        abort_if($intervention->invoice, 409, 'Factura există deja.');

        $validated = $request->validate([
            'total' => 'required|numeric|min:0.01',
        ]);

        $lastNumber = Invoice::max('id') + 1;

        Invoice::create([
            'intervention_id' => $intervention->id,
            'number' => 'FA-' . str_pad($lastNumber, 5, '0', STR_PAD_LEFT),
            'total' => $validated['total'],
            'issued_at' => now(),
        ]);

        // Also set final_cost on intervention if not set
        if (!$intervention->final_cost) {
            $intervention->update(['final_cost' => $validated['total']]);
        }

        return back()->with('success', 'Factură emisă cu succes.');
    }

    public function settings(Request $request): View
    {
        $service = $this->getService($request);
        return view('service.settings', compact('service'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $service = $this->getService($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);

        $service->update($validated);

        return back()->with('success', 'Setările au fost salvate.');
    }
}
