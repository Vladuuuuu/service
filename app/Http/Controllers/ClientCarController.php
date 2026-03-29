<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientCarController extends Controller
{
    public function index(Request $request): View
    {
        $cars = $request->user()->cars()->withCount('interventions')->get();

        return view('client.cars.index', compact('cars'));
    }

    public function create(): View
    {
        return view('client.cars.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'plate' => 'required|string|max:20|unique:cars,plate',
            'km_current' => 'required|integer|min:0',
        ]);

        $request->user()->cars()->create($validated);

        return redirect()->route('client.cars.index')
            ->with('success', 'Mașina a fost adăugată cu succes!');
    }

    public function show(Request $request, Car $car): View
    {
        // Verificăm că mașina aparține utilizatorului autentificat
        abort_unless($car->user_id === $request->user()->id, 403);

        $car->load(['interventions' => fn($q) => $q->with('service')->latest(), 'interventions.invoice']);

        return view('client.cars.show', compact('car'));
    }

    public function edit(Request $request, Car $car): View
    {
        abort_unless($car->user_id === $request->user()->id, 403);

        return view('client.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car): RedirectResponse
    {
        abort_unless($car->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'plate' => 'required|string|max:20|unique:cars,plate,' . $car->id,
            'km_current' => 'required|integer|min:0',
        ]);

        $car->update($validated);

        return redirect()->route('client.cars.show', $car)
            ->with('success', 'Datele mașinii au fost actualizate!');
    }

    public function destroy(Request $request, Car $car): RedirectResponse
    {
        abort_unless($car->user_id === $request->user()->id, 403);

        $car->delete();

        return redirect()->route('client.cars.index')
            ->with('success', 'Mașina a fost ștearsă.');
    }

    public function historyPdf(Request $request, Car $car)
    {
        abort_unless($car->user_id === $request->user()->id, 403);

        $car->load(['interventions' => fn($q) => $q->with('service', 'invoice')->orderBy('created_at'), 'user']);

        $pdf = Pdf::loadView('client.cars.history-pdf', compact('car'));

        return $pdf->download("istoric-{$car->plate}.pdf");
    }
}
