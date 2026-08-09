<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Itinerary;
use App\Models\Member;
use App\Models\Trip;

class DashboardController extends Controller
{
    public function index()
    {
        $trip = Trip::first();
        $members = Member::all();
        $totalMembers = $members->count() ?: 8;

        $totalExpenses = $trip ? $trip->totalExpenses() : 0;
        $totalFund = $trip ? $trip->total_fund : 0;
        $remaining = $totalFund - $totalExpenses;
        $progress = $totalFund > 0 ? ($totalExpenses / $totalFund) * 100 : 0;

        $upcomingItineraries = Itinerary::where('status', '!=', 'done')
            ->where('status', '!=', 'skip')
            ->orderBy('scheduled_time')
            ->limit(3)
            ->get();

        $recentExpenses = Expense::with('member')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'trip',
            'members',
            'totalMembers',
            'totalExpenses',
            'totalFund',
            'remaining',
            'progress',
            'upcomingItineraries',
            'recentExpenses'
        ));
    }
}
