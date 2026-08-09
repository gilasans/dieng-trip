<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Itinerary;
use App\Models\Trip;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function finance()
    {
        $trip = Trip::first();
        $expenses = Expense::latest()->get();
        $totalExpenses = $expenses->sum('amount');
        
        $categorySummary = $expenses->groupBy('category')->map(function ($group) {
            return $group->sum('amount');
        });

        $printDate = now()->format('d M Y, H:i');

        return view('reports.finance', compact('trip', 'expenses', 'totalExpenses', 'categorySummary', 'printDate'));
    }

    public function itinerary()
    {
        $trip = Trip::first();
        $itineraries = Itinerary::orderBy('day_number')->orderBy('scheduled_time')->get();
        
        $days = $itineraries->groupBy('day_number');
        $printDate = now()->format('d M Y, H:i');

        return view('reports.itinerary', compact('trip', 'days', 'printDate'));
    }
}
