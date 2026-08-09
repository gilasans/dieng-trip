@extends('layouts.app')
@section('title', 'Members - Dieng Trip')
@section('header', '👥 Members')
@section('subheader', 'Peserta perjalanan')

@section('content')
<div class="space-y-4">

    {{-- Add Member Button --}}
    <button onclick="openModal('member-modal')" class="w-full card p-3.5 flex items-center justify-center gap-2 text-emerald-600 font-semibold text-sm border-2 border-dashed border-emerald-200 hover:bg-emerald-50 hover:border-emerald-400 transition-all duration-300 rounded-2xl">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Tambah Member
    </button>

    {{-- Stats --}}
    <div class="card p-4 animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <span class="text-2xl">👨‍👩‍👧‍👦</span>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $members->count() }}</p>
                <p class="text-xs text-gray-500">Total Peserta</p>
            </div>
        </div>
    </div>

    {{-- Member List --}}
    <div class="space-y-2">
        @forelse($members as $index => $member)
        <div class="card p-4 flex items-center gap-3 animate-fade-in" style="animation-delay: {{ $index * 0.05 }}s" id="member-{{ $member->id }}">
            <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background: {{ $member->avatar_color }}">
                {{ $member->initials }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 text-sm">{{ $member->name }}</p>
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    {{ $member->whatsapp }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-900">{{ $member->expenses_count ?? 0 }} transaksi</p>
                <p class="text-[10px] text-gray-500">Rp {{ number_format($member->expenses_sum_amount ?? 0, 0, ',', '.') }}</p>
            </div>
            <button onclick="deleteMember({{ $member->id }})" class="text-gray-300 hover:text-red-500 transition-colors ml-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
        @empty
        <div class="text-center py-8">
            <span class="text-5xl">👤</span>
            <p class="text-gray-500 text-sm mt-3">Belum ada member</p>
            <p class="text-gray-400 text-xs">Tambahkan peserta perjalanan</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Add Member Modal --}}
<div id="member-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-handle"></div>
        <h3 class="text-lg font-bold text-gray-900 mb-5">👤 Tambah Member</h3>

        <form id="member-form" class="space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Contoh: Budi Santoso">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Nomor WhatsApp</label>
                <input type="tel" name="whatsapp" required placeholder="08xxxxxxxxxx">
            </div>

            <button type="submit" id="submit-member" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-500/25 active:scale-[0.98]">
                Tambah Member
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('member-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-member');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    const formData = new FormData(this);
    const body = Object.fromEntries(formData);

    try {
        const data = await ajax('{{ route("members.store") }}', {
            method: 'POST',
            body,
        });
        showToast(data.message || 'Member ditambahkan! 👤');
        closeModal('member-modal');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast(error.data?.message || 'Gagal menambahkan', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Tambah Member';
    }
});

async function deleteMember(id) {
    const isConfirmed = await customConfirm('Hapus member ini? Data terkait juga akan dihapus.', 'Hapus Member');
    if (!isConfirmed) return;
    try {
        await ajax(`/members/${id}`, { method: 'DELETE' });
        document.getElementById(`member-${id}`).remove();
        showToast('Member dihapus');
    } catch (error) {
        showToast('Gagal menghapus', 'error');
    }
}
</script>
@endsection
