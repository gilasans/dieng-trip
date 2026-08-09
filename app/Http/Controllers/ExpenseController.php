<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Member;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $trip = Trip::first();
        $expenses = Expense::latest()->get();
        $categories = ['BBM', 'Tol', 'Makan', 'Tiket', 'Parkir', 'Lainnya'];

        $totalExpenses = $expenses->sum('amount');
        
        // Category summary
        $categorySummary = $expenses->groupBy('category')->map(function ($group) {
            return $group->sum('amount');
        });

        return view('expenses.index', compact(
            'trip',
            'expenses',
            'categories',
            'totalExpenses',
            'categorySummary'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:BBM,Tol,Makan,Tiket,Parkir,Lainnya',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'receipt_photo' => 'nullable|image|max:5120',
        ]);

        $trip = Trip::first();
        $validated['trip_id'] = $trip->id;
        $validated['member_id'] = null;

        if ($request->hasFile('receipt_photo')) {
            $validated['receipt_photo'] = $request->file('receipt_photo')->store('receipts', 'public');
        }

        $expense = Expense::create($validated);

        $totalExpenses = Expense::sum('amount');

        return response()->json([
            'success' => true,
            'expense' => $expense,
            'totalExpenses' => $totalExpenses,
            'remaining' => $trip->total_fund - $totalExpenses,
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|in:BBM,Tol,Makan,Tiket,Parkir,Lainnya',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'receipt_photo' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('receipt_photo')) {
            if ($expense->receipt_photo) {
                Storage::disk('public')->delete($expense->receipt_photo);
            }
            $validated['receipt_photo'] = $request->file('receipt_photo')->store('receipts', 'public');
        }

        $expense->update($validated);

        $trip = Trip::first();
        $totalExpenses = Expense::sum('amount');

        return response()->json([
            'success' => true,
            'expense' => $expense,
            'totalExpenses' => $totalExpenses,
            'remaining' => $trip->total_fund - $totalExpenses,
        ]);
    }

    public function destroy(Expense $expense)
    {
        if ($expense->receipt_photo) {
            Storage::disk('public')->delete($expense->receipt_photo);
        }
        $expense->delete();

        return response()->json(['success' => true]);
    }
}
