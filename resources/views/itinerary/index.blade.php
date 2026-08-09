@extends('layouts.app')
@section('title', 'Itinerary - Dieng Trip')
@section('header', '📋 Itinerary')
@section('subheader', 'Rundown perjalanan')

@section('content')
<div class="space-y-4">

    {{-- Day Tabs --}}
    <div class="flex gap-2 overflow-x-auto pb-1 -mx-4 px-4 scrollbar-hide">
        @for($day = 1; $day <= $daysCount; $day++)
        <button onclick="switchDay({{ $day }})" id="day-tab-{{ $day }}"
            class="day-tab flex-shrink-0 px-5 py-2 rounded-xl text-sm font-semibold transition-all {{ $day === 1 ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-white text-gray-600 border border-gray-200' }}">
            Hari {{ $day }}
            @if($trip)
                <span class="text-[10px] block font-normal opacity-80">{{ $trip->start_date->copy()->addDays($day-1)->format('d M') }}</span>
            @endif
        </button>
        @endfor
    </div>

    {{-- Add Item Button --}}
    <div class="flex gap-2">
        <button onclick="openAddItineraryModal()" class="flex-1 card p-3.5 flex items-center justify-center gap-2 text-emerald-600 font-semibold text-sm border-2 border-dashed border-emerald-200 hover:bg-emerald-50 hover:border-emerald-400 transition-all duration-300 rounded-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Kegiatan
        </button>
        <a href="{{ route('report.itinerary') }}" target="_blank" class="card p-3.5 flex items-center justify-center gap-2 text-indigo-600 font-semibold text-sm border-2 border-dashed border-indigo-200 hover:bg-indigo-50 hover:border-indigo-400 transition-all duration-300 rounded-2xl whitespace-nowrap px-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 00-2-2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Rundown
        </a>
    </div>

    {{-- Itinerary Timeline --}}
    @for($day = 1; $day <= $daysCount; $day++)
    <div id="day-content-{{ $day }}" class="day-content {{ $day !== 1 ? 'hidden' : '' }}">
        @if(isset($itineraries[$day]) && $itineraries[$day]->count() > 0)
        <div class="space-y-3">
            @foreach($itineraries[$day] as $index => $item)
            <div class="card p-4 flex gap-3 animate-fade-in" id="itinerary-{{ $item->id }}" style="animation-delay: {{ $index * 0.05 }}s">
                {{-- Timeline connector --}}
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold
                        {{ $item->status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($item->status === 'on_progress' ? 'bg-yellow-100 text-yellow-700' : ($item->status === 'skip' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ $item->scheduled_time->format('H:i') }}
                    </div>
                    @if(!$loop->last)
                    <div class="w-0.5 h-full min-h-[20px] bg-gray-200 mt-1"></div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0 pb-2">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h4 class="font-semibold text-sm text-gray-900 {{ $item->status === 'done' ? 'line-through opacity-60' : '' }}">{{ $item->title }}</h4>
                            @if($item->location)
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $item->location }}
                            </p>
                            @endif
                        </div>
                        <button onclick='openEditItineraryModal(@json($item))' class="text-gray-300 hover:text-emerald-500 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="deleteItinerary({{ $item->id }})" class="text-gray-300 hover:text-red-500 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Status Buttons --}}
                    <div class="flex gap-1.5 mt-2 flex-wrap">
                        @foreach(['planned' => '📋 Planned', 'on_progress' => '🔄 Progress', 'done' => '✅ Done', 'skip' => '⏭️ Skip'] as $status => $label)
                        <button onclick="updateStatus({{ $item->id }}, '{{ $status }}')"
                            class="status-btn px-2.5 py-1 rounded-lg text-[10px] font-semibold transition-all
                            {{ $item->status === $status ? ($status === 'done' ? 'bg-emerald-500 text-white' : ($status === 'on_progress' ? 'bg-yellow-400 text-yellow-900' : ($status === 'skip' ? 'bg-red-400 text-white' : 'bg-blue-500 text-white'))) : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <span class="text-4xl">🗓️</span>
            <p class="text-gray-500 text-sm mt-2">Belum ada jadwal untuk Hari {{ $day }}</p>
            <p class="text-gray-400 text-xs">Tambah kegiatan atau pilih dari destinasi</p>
        </div>
        @endif
    </div>
    @endfor
</div>

{{-- Add/Edit Itinerary Modal --}}
<div id="itinerary-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-handle"></div>
        <h3 id="itinerary-modal-title" class="text-lg font-bold text-gray-900 mb-5">📋 Tambah Kegiatan</h3>

        <form id="itinerary-form" class="space-y-5">
            <input type="hidden" name="_method" id="itinerary-method" value="POST">
            <input type="hidden" id="itinerary-id" value="">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Hari ke</label>
                <select name="day_number" id="modal-day-number" required>
                    @for($day = 1; $day <= $daysCount; $day++)
                    <option value="{{ $day }}" data-date="{{ $trip ? $trip->start_date->copy()->addDays($day-1)->format('Y-m-d') : '' }}">Hari {{ $day }} ({{ $trip ? $trip->start_date->copy()->addDays($day-1)->format('d M Y') : '' }})</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Nama Kegiatan</label>
                <input type="text" name="title" id="itinerary-title" required placeholder="Contoh: Sunrise di Sikunir">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Lokasi</label>
                <input type="text" name="location" id="itinerary-location" placeholder="Bukit Sikunir">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Jam</label>
                <input type="time" name="scheduled_time" id="itinerary-time" required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Catatan</label>
                <textarea name="notes" id="itinerary-notes" rows="2" placeholder="Catatan tambahan..." class="resize-none"></textarea>
            </div>

            <button type="submit" id="submit-itinerary" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-500/25 active:scale-[0.98]">
                Simpan Kegiatan
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentDay = 1;

function openAddItineraryModal() {
    document.getElementById('itinerary-modal-title').innerText = '📋 Tambah Kegiatan';
    document.getElementById('itinerary-form').reset();
    document.getElementById('itinerary-method').value = 'POST';
    document.getElementById('itinerary-id').value = '';
    document.getElementById('modal-day-number').value = currentDay;
    document.getElementById('itinerary-time').value = '08:00';
    openModal('itinerary-modal');
}

function openEditItineraryModal(itinerary) {
    document.getElementById('itinerary-modal-title').innerText = '✏️ Edit Kegiatan';
    document.getElementById('itinerary-method').value = 'PUT';
    document.getElementById('itinerary-id').value = itinerary.id;
    
    document.getElementById('modal-day-number').value = itinerary.day_number;
    document.getElementById('itinerary-title').value = itinerary.title;
    document.getElementById('itinerary-location').value = itinerary.location || '';
    
    // Parse time from ISO string or similar "2026-08-22T08:00:00.000000Z"
    const dateObj = new Date(itinerary.scheduled_time);
    let hours = dateObj.getHours().toString().padStart(2, '0');
    let minutes = dateObj.getMinutes().toString().padStart(2, '0');
    // For cases where time parsing might be shifted by timezone, we can also parse the raw string
    // if it comes as '2026-08-22 08:00:00' from Laravel (not cast to ISO):
    if (typeof itinerary.scheduled_time === 'string' && itinerary.scheduled_time.includes(' ')) {
        const timePart = itinerary.scheduled_time.split(' ')[1];
        hours = timePart.split(':')[0];
        minutes = timePart.split(':')[1];
    }
    
    document.getElementById('itinerary-time').value = `${hours}:${minutes}`;
    document.getElementById('itinerary-notes').value = itinerary.notes || '';
    
    openModal('itinerary-modal');
}

function switchDay(day) {
    document.querySelectorAll('.day-tab').forEach(tab => {
        tab.className = tab.className.replace('bg-emerald-500 text-white shadow-lg shadow-emerald-500/30', 'bg-white text-gray-600 border border-gray-200');
    });
    document.getElementById(`day-tab-${day}`).className = document.getElementById(`day-tab-${day}`).className.replace('bg-white text-gray-600 border border-gray-200', 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30');

    document.querySelectorAll('.day-content').forEach(content => content.classList.add('hidden'));
    document.getElementById(`day-content-${day}`).classList.remove('hidden');

    currentDay = day;
}

async function updateStatus(id, status) {
    try {
        await ajax(`/itinerary/${id}/status`, {
            method: 'PATCH',
            body: { status },
        });
        showToast('Status updated! ' + (status === 'done' ? '✅' : ''));
        setTimeout(() => location.reload(), 300);
    } catch (error) {
        showToast('Gagal update status', 'error');
    }
}

async function deleteItinerary(id) {
    const isConfirmed = await customConfirm('Hapus kegiatan ini?', 'Hapus Kegiatan');
    if (!isConfirmed) return;
    try {
        await ajax(`/itinerary/${id}`, { method: 'DELETE' });
        document.getElementById(`itinerary-${id}`).remove();
        showToast('Kegiatan dihapus');
    } catch (error) {
        showToast('Gagal menghapus', 'error');
    }
}

document.getElementById('itinerary-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-itinerary');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    const formData = new FormData(this);
    const body = Object.fromEntries(formData);
    
    const method = document.getElementById('itinerary-method').value;
    const id = document.getElementById('itinerary-id').value;
    const url = method === 'PUT' ? `/itinerary/${id}` : '{{ route("itinerary.store") }}';
    
    // Add _method inside body for PUT since we're sending as JSON
    if (method === 'PUT') {
        body._method = 'PUT';
    }

    try {
        await ajax(url, {
            method: 'POST',
            body,
        });
        showToast('Kegiatan berhasil disimpan! 📋');
        closeModal('itinerary-modal');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast(error.data?.message || 'Gagal menyimpan', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Simpan Kegiatan';
    }
});
</script>
@endsection
