@extends('layouts.app')
@section('title', 'Emergency Info - Dieng Trip')
@section('header', '🆘 Emergency Info')
@section('subheader', 'Informasi darurat')

@section('content')
<div class="space-y-4">

    <p class="text-sm text-gray-500 animate-fade-in">Informasi penting untuk keadaan darurat selama perjalanan.</p>

    {{-- SPBU --}}
    <div class="animate-fade-in delay-100">
        <h3 class="font-semibold text-gray-900 text-sm mb-2 flex items-center gap-2">⛽ SPBU Terdekat</h3>
        <div class="space-y-2">
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">⛽</div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900">SPBU Dieng</p>
                        <p class="text-xs text-gray-500">Jl. Raya Dieng, Batur, Banjarnegara</p>
                    </div>
                    <a href="tel:" class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>
                </div>
            </div>
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">⛽</div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900">SPBU Wonosobo</p>
                        <p class="text-xs text-gray-500">Jl. A. Yani, Wonosobo Timur</p>
                    </div>
                    <a href="tel:" class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bengkel --}}
    <div class="animate-fade-in delay-200">
        <h3 class="font-semibold text-gray-900 text-sm mb-2 flex items-center gap-2">🔧 Bengkel</h3>
        <div class="space-y-2">
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">🔧</div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900">Bengkel Jaya Motor</p>
                        <p class="text-xs text-gray-500">Jl. Raya Dieng Km 5, Batur</p>
                        <p class="text-[10px] text-gray-400">Buka 07:00 - 17:00</p>
                    </div>
                    <a href="tel:" class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>
                </div>
            </div>
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">🔧</div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900">Bengkel Sejahtera</p>
                        <p class="text-xs text-gray-500">Jl. Raya Wonosobo-Dieng</p>
                        <p class="text-[10px] text-gray-400">Buka 08:00 - 18:00</p>
                    </div>
                    <a href="tel:" class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Rumah Sakit --}}
    <div class="animate-fade-in delay-300">
        <h3 class="font-semibold text-gray-900 text-sm mb-2 flex items-center gap-2">🏥 Rumah Sakit & Klinik</h3>
        <div class="space-y-2">
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">🏥</div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900">RSUD KRT Setjonegoro</p>
                        <p class="text-xs text-gray-500">Jl. Rumah Sakit No.1, Wonosobo</p>
                        <p class="text-[10px] text-emerald-500 font-medium">24 Jam</p>
                    </div>
                    <a href="tel:02863321123" class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>
                </div>
            </div>
            <div class="card p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">🏥</div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900">Puskesmas Batur</p>
                        <p class="text-xs text-gray-500">Kec. Batur, Banjarnegara</p>
                        <p class="text-[10px] text-gray-400">Buka 08:00 - 16:00</p>
                    </div>
                    <a href="tel:" class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Emergency Contacts --}}
    <div class="animate-fade-in delay-400">
        <h3 class="font-semibold text-gray-900 text-sm mb-2 flex items-center gap-2">📞 Nomor Darurat</h3>
        <div class="grid grid-cols-2 gap-2">
            <a href="tel:112" class="card p-3 text-center hover:bg-red-50 transition-colors">
                <p class="text-2xl">🚨</p>
                <p class="font-bold text-gray-900 text-sm">112</p>
                <p class="text-[10px] text-gray-500">Darurat Umum</p>
            </a>
            <a href="tel:118" class="card p-3 text-center hover:bg-red-50 transition-colors">
                <p class="text-2xl">🚑</p>
                <p class="font-bold text-gray-900 text-sm">118</p>
                <p class="text-[10px] text-gray-500">Ambulans</p>
            </a>
            <a href="tel:113" class="card p-3 text-center hover:bg-red-50 transition-colors">
                <p class="text-2xl">🚒</p>
                <p class="font-bold text-gray-900 text-sm">113</p>
                <p class="text-[10px] text-gray-500">Pemadam</p>
            </a>
            <a href="tel:110" class="card p-3 text-center hover:bg-red-50 transition-colors">
                <p class="text-2xl">🚔</p>
                <p class="font-bold text-gray-900 text-sm">110</p>
                <p class="text-[10px] text-gray-500">Polisi</p>
            </a>
        </div>
    </div>

    {{-- Tips --}}
    <div class="card p-4 bg-amber-50 border border-amber-200 animate-fade-in delay-500">
        <h3 class="font-semibold text-gray-900 text-sm mb-2 flex items-center gap-2">💡 Tips Keselamatan</h3>
        <ul class="space-y-1.5 text-xs text-gray-600">
            <li class="flex items-start gap-2">
                <span class="flex-shrink-0">🧥</span>
                <span>Bawa jaket tebal, suhu Dieng bisa mencapai 0°C</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="flex-shrink-0">🌫️</span>
                <span>Hati-hati kabut tebal saat berkendara di pagi/malam hari</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="flex-shrink-0">⛽</span>
                <span>Isi bensin penuh sebelum naik ke Dieng</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="flex-shrink-0">🫁</span>
                <span>Hindari terlalu dekat dengan kawah aktif (gas beracun)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="flex-shrink-0">💧</span>
                <span>Bawa air minum yang cukup dan obat-obatan pribadi</span>
            </li>
        </ul>
    </div>

</div>
@endsection
