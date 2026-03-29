<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $allServices = Service::select('id', 'name', 'lat', 'lng', 'rating', 'phone')->get();
        $services = $allServices->sortByDesc('rating')->take(3)->values();
        $mapMarkers = $allServices->map(function ($s) {
            return [
                'name' => $s->name,
                'lat' => $s->lat,
                'lng' => $s->lng,
                'rating' => $s->rating,
                'phone' => $s->phone,
            ];
        })->values();

        return view('landing', compact('services', 'mapMarkers'));
    }
}
