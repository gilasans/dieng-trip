@extends('layouts.app')
@section('title', 'Gallery - Dieng Trip')
@section('header', '📸 Gallery')
@section('subheader', 'Momen perjalanan')

@section('content')
<div class="space-y-4">

    {{-- Upload Button --}}
    <button onclick="openModal('upload-modal')" class="w-full card p-3.5 flex items-center justify-center gap-2 text-emerald-600 font-semibold text-sm border-2 border-dashed border-emerald-200 hover:bg-emerald-50 hover:border-emerald-400 transition-all duration-300 rounded-2xl">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Upload Foto / Video
    </button>

    {{-- Location Filter --}}
    <div class="flex gap-2 overflow-x-auto pb-1 -mx-4 px-4 scrollbar-hide">
        <a href="{{ route('gallery.index') }}" class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-all {{ !request('location') ? 'bg-emerald-500 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('gallery.index', ['best_moment' => 1]) }}" class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-all {{ request('best_moment') ? 'bg-amber-500 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
            ⭐ Best Moment
        </a>
        @foreach($locations as $loc)
        <a href="{{ route('gallery.index', ['location' => $loc]) }}" class="flex-shrink-0 px-4 py-1.5 rounded-full text-xs font-semibold transition-all {{ request('location') === $loc ? 'bg-emerald-500 text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
            {{ $loc }}
        </a>
        @endforeach
    </div>

    {{-- Gallery Grid --}}
    @if($galleries->count() > 0)
    <div class="gallery-grid">
        @foreach($galleries as $gallery)
        <div class="gallery-item" id="gallery-{{ $gallery->id }}">
            @if($gallery->file_type === 'video')
                <video src="{{ asset('storage/' . $gallery->file_path) }}" class="w-full h-full object-cover" onclick="openLightbox('{{ asset('storage/' . $gallery->file_path) }}')"></video>
                <div class="absolute top-1 left-1 bg-black/50 rounded-full p-1">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
            @else
                <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="{{ $gallery->caption }}" onclick="openLightbox('{{ asset('storage/' . $gallery->file_path) }}')" loading="lazy">
            @endif

            {{-- Best Moment Badge --}}
            @if($gallery->is_best_moment)
            <div class="absolute top-1 right-1 bg-amber-400 rounded-full p-1">
                <span class="text-[10px]">⭐</span>
            </div>
            @endif

            {{-- Actions overlay --}}
            <div class="absolute inset-0 bg-black/0 hover:bg-black/30 transition-colors flex items-end justify-between p-1.5 opacity-0 hover:opacity-100">
                <div class="flex gap-1">
                    <button onclick="event.stopPropagation(); toggleBest({{ $gallery->id }})" class="bg-white/90 rounded-full p-1.5 text-[10px]">
                        {{ $gallery->is_best_moment ? '⭐' : '☆' }}
                    </button>
                    <a href="{{ asset('storage/' . $gallery->file_path) }}" download onclick="event.stopPropagation()" class="bg-blue-500/90 rounded-full p-1.5 text-white" title="Download">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                </div>
                <button onclick="event.stopPropagation(); deleteGallery({{ $gallery->id }})" class="bg-red-500/90 rounded-full p-1.5">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <span class="text-5xl">📷</span>
        <p class="text-gray-500 text-sm mt-3">Belum ada foto</p>
        <p class="text-gray-400 text-xs">Upload momen terbaik perjalananmu!</p>
    </div>
    @endif
</div>

{{-- Upload Modal --}}
<div id="upload-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-handle"></div>
        <h3 class="text-lg font-bold text-gray-900 mb-5">📸 Upload Media</h3>

        <form id="upload-form" class="space-y-5" enctype="multipart/form-data">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Pilih File</label>
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/30 transition-all duration-300" onclick="document.getElementById('file-input').click()">
                    <span class="text-3xl" id="upload-icon">📁</span>
                    <p class="text-sm text-gray-500 mt-2 font-medium">Tap untuk pilih foto/video</p>
                    <p class="text-xs text-gray-400">Bisa pilih beberapa sekaligus</p>
                    <input type="file" id="file-input" name="files[]" multiple accept="image/*,video/*" class="hidden" onchange="showFilePreview(this)">
                </div>
                <p id="file-count" class="text-xs text-emerald-600 mt-1.5 font-medium hidden"></p>
                {{-- Preview Grid --}}
                <div id="file-preview-grid" class="hidden mt-3 grid grid-cols-3 gap-2"></div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Lokasi</label>
                <select name="location_tag">
                    <option value="">Pilih lokasi (opsional)...</option>
                    @foreach($destinations as $dest)
                        <option value="{{ $dest }}">{{ $dest }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Caption (opsional)</label>
                <input type="text" name="caption" placeholder="Momen indah di...">
            </div>

            <button type="submit" id="submit-upload" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-500/25 active:scale-[0.98]">
                Upload
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showFilePreview(input) {
    const count = input.files.length;
    const el = document.getElementById('file-count');
    el.textContent = `${count} file dipilih`;
    el.classList.remove('hidden');

    const grid = document.getElementById('file-preview-grid');
    grid.innerHTML = '';

    if (count === 0) {
        grid.classList.add('hidden');
        return;
    }
    grid.classList.remove('hidden');

    Array.from(input.files).forEach(function(file) {
        const wrapper = document.createElement('div');
        wrapper.className = 'relative rounded-xl overflow-hidden bg-gray-100 aspect-square';

        if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.className = 'w-full h-full object-cover';
            video.muted = true;
            const badge = document.createElement('div');
            badge.className = 'absolute inset-0 flex items-center justify-center';
            badge.innerHTML = '<span class="text-2xl">▶️</span>';
            wrapper.appendChild(video);
            wrapper.appendChild(badge);
        } else {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-full h-full object-cover';
            img.alt = file.name;
            wrapper.appendChild(img);
        }

        const nameBadge = document.createElement('p');
        nameBadge.className = 'absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[9px] truncate px-1 py-0.5';
        nameBadge.textContent = file.name;
        wrapper.appendChild(nameBadge);

        grid.appendChild(wrapper);
    });
}

document.getElementById('upload-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-upload');
    const fileInput = document.getElementById('file-input');

    if (!fileInput.files.length) {
        showToast('Pilih file terlebih dahulu', 'warning');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Uploading...';

    const formData = new FormData(this);

    try {
        const data = await ajax('{{ route("gallery.store") }}', {
            method: 'POST',
            body: formData,
        });
        showToast(`${data.count} file berhasil diupload! 📸`);
        closeModal('upload-modal');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast(error.data?.message || 'Gagal upload', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Upload';
    }
});

async function toggleBest(id) {
    try {
        const data = await ajax(`/gallery/${id}/best-moment`, { method: 'PATCH' });
        showToast(data.is_best_moment ? 'Ditandai Best Moment! ⭐' : 'Best Moment dihapus');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast('Gagal update', 'error');
    }
}

async function deleteGallery(id) {
    const isConfirmed = await customConfirm('Hapus media ini?', 'Hapus Media');
    if (!isConfirmed) return;
    try {
        await ajax(`/gallery/${id}`, { method: 'DELETE' });
        document.getElementById(`gallery-${id}`).remove();
        showToast('Media dihapus');
    } catch (error) {
        showToast('Gagal menghapus', 'error');
    }
}
</script>
@endsection
