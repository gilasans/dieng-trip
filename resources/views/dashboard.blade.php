@extends('layouts.app')
@section('title', 'Dashboard - Dieng Trip Manager')
@section('header', '🏔️ Dieng Trip')
@section('subheader', 'Liburan Keluarga ke Dieng')

@section('header-actions')
    <a href="{{ route('emergency') }}" class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
    </a>
@endsection

@section('content')
<div class="space-y-4">
    <div class="card-gradient p-5 animate-fade-in">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-emerald-100 text-xs font-medium">Total Dana Terkumpul</p>
                <p class="text-2xl font-extrabold text-white">Rp {{ number_format($totalFund, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                <span class="text-2xl">💰</span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-white/15 rounded-xl p-3">
                <p class="text-emerald-100 text-xs">Pengeluaran</p>
                <p class="text-lg font-bold text-white">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white/15 rounded-xl p-3">
                <p class="text-emerald-100 text-xs">Sisa Kas</p>
                <p class="text-lg font-bold {{ $remaining >= 0 ? 'text-white' : 'text-red-200' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</p>
            </div>
        </div>

        <div>
            <div class="flex justify-between text-xs text-emerald-100 mb-1">
                <span>Progress Pengeluaran</span>
                <span>{{ number_format(min($progress, 100), 1) }}%</span>
            </div>
            <div class="progress-bar" style="background: rgba(255,255,255,0.2);">
                <div class="progress-bar-fill" style="width: {{ min($progress, 100) }}%; background: linear-gradient(90deg, rgba(255,255,255,0.6), white);"></div>
            </div>
        </div>
    </div>

    @if($trip)
    <div class="card p-4 animate-fade-in delay-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <span class="text-xl">🗓️</span>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 text-sm">{{ $trip->name }}</h3>
                <p class="text-xs text-gray-500">{{ $trip->start_date->format('d M') }} - {{ $trip->end_date->format('d M Y') }}</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div class="bg-gray-50 rounded-lg p-2 text-center">
                <p class="text-lg font-bold text-gray-900">{{ $trip->days_count }}</p>
                <p class="text-[10px] text-gray-500">Hari</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2 text-center">
                <p class="text-lg font-bold text-gray-900">{{ $totalMembers }}</p>
                <p class="text-[10px] text-gray-500">Orang</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2 text-center">
                <p class="text-lg font-bold text-gray-900">1</p>
                <p class="text-[10px] text-gray-500">Kendaraan</p>
            </div>
        </div>
         
        @if($trip->vehicle_info)
            <p class="text-xs text-gray-500 mt-2 text-center">🚗 {{ $trip->vehicle_info }}</p>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-4 gap-2 animate-fade-in delay-200">
        <a href="{{ route('expenses.index') }}" class="card p-3 flex flex-col items-center gap-1 text-center">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                <span class="text-xl">💸</span>
            </div>
            <span class="text-[10px] font-medium text-gray-700">Catat Biaya</span>
        </a>
        <a href="{{ route('itinerary.index') }}" class="card p-3 flex flex-col items-center gap-1 text-center">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <span class="text-xl">📋</span>
            </div>
            <span class="text-[10px] font-medium text-gray-700">Itinerary</span>
        </a>
        <a href="{{ route('gallery.index') }}" class="card p-3 flex flex-col items-center gap-1 text-center">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <span class="text-xl">📸</span>
            </div>
            <span class="text-[10px] font-medium text-gray-700">Gallery</span>
        </a>
        <a href="{{ route('emergency') }}" class="card p-3 flex flex-col items-center gap-1 text-center">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                <span class="text-xl">🆘</span>
            </div>
            <span class="text-[10px] font-medium text-gray-700">Darurat</span>
        </a>
    </div>

    @if($upcomingItineraries->count() > 0)
    <div class="animate-fade-in delay-300">
        <h3 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
            <span>📍</span> Jadwal Mendatang
        </h3>
        <div class="space-y-2">
            @foreach($upcomingItineraries as $item)
            <div class="card p-3 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg
                    {{ $item->status === 'planned' ? 'bg-blue-100' : ($item->status === 'on_progress' ? 'bg-yellow-100' : 'bg-gray-100') }}">
                    {{ $item->status_icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm text-gray-900 truncate">{{ $item->title }}</p>
                    <p class="text-xs text-gray-500">Hari {{ $item->day_number }} • {{ $item->scheduled_time->format('H:i') }}</p>
                </div>
                <span class="status-badge {{ $item->status_badge }}">{{ $item->status_label }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($recentExpenses->count() > 0)
    <div class="animate-fade-in delay-400">
        <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                <span>💳</span> Pengeluaran Terakhir
            </h3>
            <a href="{{ route('expenses.index') }}" class="text-xs text-emerald-600 font-medium">Lihat Semua →</a>
        </div>
        <div class="space-y-2">
            @foreach($recentExpenses as $expense)
            <div class="card p-3 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $expense->category_color }}">
                    {{ $expense->category_icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm text-gray-900 truncate">{{ $expense->description ?: $expense->category }}</p>
                    <p class="text-xs text-gray-500">Dana Kas • {{ $expense->created_at->diffForHumans() }}</p>
                </div>
                <p class="font-semibold text-sm text-gray-900">Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
