@extends('layouts.app')
@section('title', 'Destinasi - Dieng Trip')
@section('header', '📍 Destinasi Wisata')
@section('subheader', 'Eksplorasi Dieng Plateau')

@section('content')
<div class="space-y-4">

    <p class="text-sm text-gray-500 animate-fade-in">Temukan tempat wisata terbaik di Dieng dan tambahkan ke itinerary perjalananmu.</p>

    {{-- Destination Cards --}}
    <div class="space-y-3">
        @foreach($destinations as $index => $dest)
        <div class="card overflow-hidden animate-fade-in" style="animation-delay: {{ $index * 0.05 }}s">
            {{-- Image Placeholder --}}
            <div class="h-40 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center relative">
                <span class="text-5xl">
                    @switch($dest->name)
                        @case('Bukit Sikunir') 🌄 @break
                        @case('Telaga Warna') 🏞️ @break
                        @case('Kawah Sikidang') 🌋 @break
                        @case('Candi Arjuna') 🛕 @break
                        @case('Telaga Menjer') 💧 @break
                        @case('Swiss Van Java') 🏔️ @break
                        @case('Taman Langit') ☁️ @break
                        @case('Pintu Langit') 🚪 @break
                        @case('Banyu Alam') 🌊 @break
                        @default 🏞️
                    @endswitch
                </span>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent p-3">
                    <h3 class="text-white font-bold text-base">{{ $dest->name }}</h3>
                </div>
            </div>

            <div class="p-4">
                <p class="text-sm text-gray-600 mb-3">{{ $dest->description }}</p>

                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="category-pill bg-blue-100 text-blue-700">
                        🕐 {{ $dest->best_time }}
                    </span>
                    <span class="category-pill bg-purple-100 text-purple-700">
                        ⏱️ {{ $dest->estimated_duration }}
                    </span>
                </div>

                <button onclick="openAddToItinerary({{ $dest->id }}, '{{ $dest->name }}')"
                    class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold text-sm rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambahkan ke Itinerary
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Add to Itinerary Modal --}}
<div id="add-itinerary-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-handle"></div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">📍 Tambah ke Itinerary</h3>
        <p class="text-sm text-gray-500 mb-5" id="dest-name-label"></p>

        <form id="add-to-itinerary-form" class="space-y-5">
            <input type="hidden" name="destination_id" id="dest-id-input">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Hari ke</label>
                <select name="day_number" required>
                    @if($trip)
                        @for($day = 1; $day <= $trip->days_count; $day++)
                        <option value="{{ $day }}">Hari {{ $day }} ({{ $trip->start_date->addDays($day-1)->format('d M Y') }})</option>
                        @endfor
                    @else
                        <option value="1">Hari 1</option>
                        <option value="2">Hari 2</option>
                        <option value="3">Hari 3</option>
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Jam</label>
                <input type="datetime-local" name="scheduled_time" required>
            </div>

            <button type="submit" id="submit-add-itinerary" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-500/25 active:scale-[0.98]">
                Tambahkan
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openAddToItinerary(destId, destName) {
    document.getElementById('dest-id-input').value = destId;
    document.getElementById('dest-name-label').textContent = destName;
    openModal('add-itinerary-modal');
}

document.getElementById('add-to-itinerary-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-add-itinerary');
    btn.disabled = true;
    btn.textContent = 'Menambahkan...';

    const formData = new FormData(this);
    const body = Object.fromEntries(formData);

    try {
        const data = await ajax('{{ route("destinations.addToItinerary") }}', {
            method: 'POST',
            body,
        });
        showToast(data.message || 'Ditambahkan ke itinerary! 📋');
        closeModal('add-itinerary-modal');
    } catch (error) {
        showToast(error.data?.message || 'Gagal menambahkan', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Tambahkan';
    }
});
</script>
@endsection
