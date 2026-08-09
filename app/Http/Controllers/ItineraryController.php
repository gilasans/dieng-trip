<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\Trip;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    public function index()
    {
        $trip = Trip::first();
        $itineraries = Itinerary::where('trip_id', $trip?->id)
            ->orderBy('day_number')
            ->orderBy('sort_order')
            ->orderBy('scheduled_time')
            ->get()
            ->groupBy('day_number');

        $daysCount = $trip ? $trip->days_count : 3;

        return view('itinerary.index', compact('trip', 'itineraries', 'daysCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'scheduled_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $trip = Trip::first();
        
        $date = $trip->start_date->copy()->addDays($validated['day_number'] - 1)->format('Y-m-d');
        $validated['scheduled_time'] = $date . ' ' . $validated['scheduled_time'];

        $validated['trip_id'] = $trip->id;
        $validated['sort_order'] = Itinerary::where('trip_id', $trip->id)
            ->where('day_number', $validated['day_number'])
            ->max('sort_order') + 1;

        $itinerary = Itinerary::create($validated);

        return response()->json([
            'success' => true,
            'itinerary' => $itinerary,
        ]);
    }

    public function update(Request $request, Itinerary $itinerary)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'scheduled_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $trip = Trip::first();
        
        $date = $trip->start_date->copy()->addDays($validated['day_number'] - 1)->format('Y-m-d');
        $validated['scheduled_time'] = $date . ' ' . $validated['scheduled_time'];

        $itinerary->update($validated);

        return response()->json([
            'success' => true,
            'itinerary' => $itinerary,
        ]);
    }

    public function updateStatus(Request $request, Itinerary $itinerary)
    {
        $validated = $request->validate([
            'status' => 'required|in:planned,on_progress,done,skip',
        ]);

        $itinerary->update($validated);

        return response()->json([
            'success' => true,
            'itinerary' => $itinerary->fresh(),
        ]);
    }

    public function upcoming()
    {
        $upcoming = Itinerary::where('status', 'planned')
            ->where('scheduled_time', '>=', now())
            ->orderBy('scheduled_time')
            ->limit(1)
            ->first();

        return response()->json([
            'upcoming' => $upcoming,
        ]);
    }

    public function destroy(Itinerary $itinerary)
    {
        $itinerary->delete();
        return response()->json(['success' => true]);
    }
}
