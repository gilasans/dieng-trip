<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Expenses
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

// Itinerary
Route::get('/itinerary', [ItineraryController::class, 'index'])->name('itinerary.index');
Route::post('/itinerary', [ItineraryController::class, 'store'])->name('itinerary.store');
Route::put('/itinerary/{itinerary}', [ItineraryController::class, 'update'])->name('itinerary.update');
Route::patch('/itinerary/{itinerary}/status', [ItineraryController::class, 'updateStatus'])->name('itinerary.updateStatus');
Route::get('/itinerary/upcoming', [ItineraryController::class, 'upcoming'])->name('itinerary.upcoming');
Route::delete('/itinerary/{itinerary}', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');

// Destinations
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::post('/destinations/add-to-itinerary', [DestinationController::class, 'addToItinerary'])->name('destinations.addToItinerary');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
Route::patch('/gallery/{gallery}/best-moment', [GalleryController::class, 'toggleBestMoment'])->name('gallery.toggleBestMoment');
Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

// Members
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::post('/members', [MemberController::class, 'store'])->name('members.store');
Route::post('/members/check', [MemberController::class, 'checkIdentity'])->name('members.check');
Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

// Emergency (static page)
Route::get('/emergency', function () {
    return view('emergency');
})->name('emergency');

// Reports
Route::get('/report/finance', [ReportController::class, 'finance'])->name('report.finance');
Route::get('/report/itinerary', [ReportController::class, 'itinerary'])->name('report.itinerary');
