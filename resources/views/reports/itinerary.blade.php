<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rundown Itinerary - Dieng Trip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
        }
        @media print {
            @page { margin: 0; }
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff;
                color: #000000;
                padding: 1.5cm;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page-break {
                page-break-before: always;
            }
            .avoid-break {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="p-4 md:p-8" onload="window.print()">

    <div class="no-print max-w-4xl mx-auto mb-6 p-4 rounded-2xl bg-indigo-950 text-white flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-400/30 flex items-center justify-center text-cyan-300 font-bold">
                📄
            </div>
            <div>
                <h3 class="text-sm font-bold">Dokumen Siap Cetak</h3>
                <p class="text-xs text-indigo-200">Klik tombol di samping untuk mencetak Rundown Itinerary.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-cyan-400 hover:bg-cyan-300 text-indigo-950 text-xs font-bold shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 00-2-2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak / PDF</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold">
                Tutup
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-md border border-gray-200">

        <div class="border-b-2 border-indigo-900 pb-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-[11px] font-bold tracking-wider uppercase mb-2">
                    DIENG TRIP MANAGER
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-indigo-950 tracking-tight">
                    RUNDOWN ITINERARY
                </h1>
                <p class="text-sm font-semibold text-gray-600 mt-1">
                    {{ $trip->name ?? 'Liburan' }} ({{ $trip ? $trip->start_date->format('d M') . ' - ' . $trip->end_date->format('d M Y') : '' }})
                </p>
            </div>
            <div class="text-left sm:text-right text-xs text-gray-500 font-mono">
                <p><span class="font-bold text-gray-700">Dicetak:</span> {{ $printDate }}</p>
                <p><span class="font-bold text-gray-700">Total Hari:</span> {{ count($days) }} Hari</p>
            </div>
        </div>

        <div class="space-y-8">
            @forelse($days as $dayNumber => $itineraries)
            <div>
                <h2 class="text-lg font-bold text-indigo-900 border-b-2 border-indigo-100 pb-2 mb-4 flex items-center gap-2 avoid-break">
                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-lg text-sm">Hari {{ $dayNumber }}</span>
                    @php
                        $firstItinerary = $itineraries->first();
                        if ($firstItinerary && $trip) {
                            $date = $trip->start_date->copy()->addDays($dayNumber - 1);
                            echo '<span class="text-gray-500 font-normal text-sm">' . $date->format('l, d F Y') . '</span>';
                        }
                    @endphp
                </h2>
                
                <div class="space-y-3">
                    @foreach($itineraries as $item)
                    <div class="flex gap-4 p-3 rounded-xl border border-gray-100 bg-gray-50 avoid-break">
                        <div class="w-16 flex-shrink-0 text-center">
                            <p class="font-bold text-indigo-900">{{ $item->scheduled_time->format('H:i') }}</p>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">{{ $item->title }}</p>
                            @if($item->location)
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <span>📍</span> {{ $item->location }}
                            </p>
                            @endif
                        </div>
                        <div class="w-24 text-right">
                            <span class="text-[10px] px-2 py-1 rounded-md font-bold uppercase
                                {{ $item->status === 'planned' ? 'bg-blue-100 text-blue-800' : ($item->status === 'on_progress' ? 'bg-yellow-100 text-yellow-800' : ($item->status === 'done' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-800')) }}
                            ">
                                {{ $item->status === 'planned' ? 'Rencana' : ($item->status === 'on_progress' ? 'Proses' : ($item->status === 'done' ? 'Selesai' : 'Lewati')) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-500">
                Belum ada jadwal yang direncanakan.
            </div>
            @endforelse
        </div>

        <div class="mt-16 pt-6 border-t-2 border-gray-200 text-center text-xs text-gray-500 avoid-break">
            <p class="mt-4 text-[10px] text-gray-400">Dicetak melalui sistem otomatis Dieng Trip Manager — {{ $printDate }}</p>
        </div>

    </div>

</body>
</html>
