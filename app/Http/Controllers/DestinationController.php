<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Trip;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::all();
        $trip = Trip::first();

        return view('destinations.index', compact('destinations', 'trip'));
    }

    public function addToItinerary(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'day_number' => 'required|integer|min:1',
            'scheduled_time' => 'required|date',
        ]);

        $destination = Destination::findOrFail($validated['destination_id']);
        $trip = Trip::first();

        $itinerary = Itinerary::create([
            'trip_id' => $trip->id,
            'day_number' => $validated['day_number'],
            'title' => $destination->name,
            'location' => $destination->name,
            'scheduled_time' => $validated['scheduled_time'],
            'notes' => $destination->description,
            'sort_order' => Itinerary::where('trip_id', $trip->id)
                ->where('day_number', $validated['day_number'])
                ->max('sort_order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'itinerary' => $itinerary,
            'message' => "{$destination->name} ditambahkan ke Hari {$validated['day_number']}",
        ]);
    }
}
