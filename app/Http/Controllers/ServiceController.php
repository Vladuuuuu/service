<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::query();

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $services = $query->orderByDesc('rating')->get();
        $cities = Service::distinct()->orderBy('city')->pluck('city');

        return view('services.index', compact('services', 'cities'));
    }

    public function show(Service $service): View
    {
        $service->loadCount('interventions');
        $cars = auth()->check() && auth()->user()->isClient()
            ? auth()->user()->cars
            : collect();

        return view('services.show', compact('service', 'cars'));
    }

    public function book(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'type' => 'required|in:ulei,revizie,frane,general',
            'description' => 'required|string|max:500',
            'scheduled_at' => 'required|date|after:today',
            'km_at_intervention' => 'nullable|integer|min:0',
        ]);

        // Verify car belongs to user
        $car = $request->user()->cars()->findOrFail($validated['car_id']);

        Intervention::create([
            'car_id' => $car->id,
            'service_id' => $service->id,
            'status' => 'pending',
            'type' => $validated['type'],
            'description' => $validated['description'],
            'scheduled_at' => $validated['scheduled_at'],
            'km_at_intervention' => $validated['km_at_intervention'] ?? $car->km_current,
        ]);

        // Update car km if provided
        if (!empty($validated['km_at_intervention']) && $validated['km_at_intervention'] > $car->km_current) {
            $car->update(['km_current' => $validated['km_at_intervention']]);
        }

        return redirect()->route('client.dashboard')
            ->with('success', "Programare trimisă către {$service->name}! Vei fi contactat pentru confirmare.");
    }
}
