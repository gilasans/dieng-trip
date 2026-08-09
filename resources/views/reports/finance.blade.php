<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Dieng Trip</title>
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
                <p class="text-xs text-indigo-200">Klik tombol di samping untuk mencetak Laporan Keuangan.</p>
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
                    LAPORAN KEUANGAN
                </h1>
                <p class="text-sm font-semibold text-gray-600 mt-1">
                    {{ $trip->name ?? 'Liburan' }} ({{ $trip ? $trip->start_date->format('d M') . ' - ' . $trip->end_date->format('d M Y') : '' }})
                </p>
            </div>
            <div class="text-left sm:text-right text-xs text-gray-500 font-mono">
                <p><span class="font-bold text-gray-700">Dicetak:</span> {{ $printDate }}</p>
                <p><span class="font-bold text-gray-700">Total Transaksi:</span> {{ $expenses->count() }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8 avoid-break">
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                <p class="text-xs font-bold text-emerald-800 uppercase tracking-wide">Total Dana (Kas)</p>
                <p class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($trip ? $trip->total_fund : 0, 0, ',', '.') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-100">
                <p class="text-xs font-bold text-rose-800 uppercase tracking-wide">Total Pengeluaran</p>
                <p class="text-xl font-bold text-rose-600 mt-1">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            </div>
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 col-span-2 md:col-span-1">
                <p class="text-xs font-bold text-blue-800 uppercase tracking-wide">Sisa Dana</p>
                <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format(($trip ? $trip->total_fund : 0) - $totalExpenses, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($categorySummary->count() > 0)
        <div class="mb-8 avoid-break">
            <h3 class="text-xs font-bold text-indigo-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">
                Ringkasan Per Kategori
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                @foreach($categorySummary as $cat => $amount)
                    <div class="flex flex-col p-3 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="text-gray-500 text-xs">{{ $cat }}</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="avoid-break">
            <h3 class="text-xs font-bold text-indigo-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">
                Rincian Transaksi Pengeluaran
            </h3>
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200 text-gray-600 bg-gray-50">
                        <th class="p-3 font-bold text-xs uppercase tracking-wider">Tanggal</th>
                        <th class="p-3 font-bold text-xs uppercase tracking-wider">Kategori</th>
                        <th class="p-3 font-bold text-xs uppercase tracking-wider">Keterangan</th>
                        <th class="p-3 font-bold text-xs uppercase tracking-wider text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $ex)
                    <tr class="border-b border-gray-100">
                        <td class="p-3 text-gray-500 text-xs">{{ $ex->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3 font-medium text-gray-700">{{ $ex->category }}</td>
                        <td class="p-3 text-gray-900">{{ $ex->description ?: '-' }}</td>
                        <td class="p-3 font-bold text-gray-900 text-right">Rp {{ number_format($ex->amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">Belum ada data pengeluaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-16 pt-6 border-t-2 border-gray-200 text-center text-xs text-gray-500 avoid-break">
            <p class="mt-4 text-[10px] text-gray-400">Dicetak melalui sistem otomatis Dieng Trip Manager — {{ $printDate }}</p>
        </div>

    </div>

</body>
</html>
