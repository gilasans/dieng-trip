@extends('layouts.app')
@section('title', 'Expense Tracker - Dieng Trip')
@section('header', '💸 Expense Tracker')
@section('subheader', 'Kelola pengeluaran trip')

@section('content')
<div class="space-y-4">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 gap-3 animate-fade-in">
        <div class="card-gradient p-4">
            <p class="text-emerald-100 text-xs font-medium">Total Pengeluaran</p>
            <p class="text-xl font-extrabold text-white mt-1" id="total-expenses">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
        </div>
        <div class="card p-4 border border-emerald-100 flex flex-col justify-center">
            <p class="text-gray-500 text-xs font-medium">Total Transaksi</p>
            <p class="text-xl font-extrabold text-emerald-600 mt-1">{{ $expenses->count() }} Trx</p>
        </div>
    </div>

    <div class="flex gap-2">
        <button onclick="openAddExpenseModal()" class="flex-1 card p-3.5 flex items-center justify-center gap-2 text-emerald-600 font-semibold text-sm border-2 border-dashed border-emerald-200 hover:bg-emerald-50 hover:border-emerald-400 transition-all duration-300 rounded-2xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Transaksi
        </button>
        <a href="{{ route('report.finance') }}" target="_blank" class="card p-3.5 flex items-center justify-center gap-2 text-indigo-600 font-semibold text-sm border-2 border-dashed border-indigo-200 hover:bg-indigo-50 hover:border-indigo-400 transition-all duration-300 rounded-2xl whitespace-nowrap px-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 00-2-2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak PDF
        </a>
    </div>

    {{-- Category Summary --}}
    @if($categorySummary->count() > 0)
    <div class="animate-fade-in delay-200">
        <h3 class="font-semibold text-gray-900 text-sm mb-2">Per Kategori</h3>
        <div class="flex gap-2 overflow-x-auto pb-2 -mx-4 px-4 scrollbar-hide">
            @php
                $catIcons = ['BBM'=>'⛽','Tol'=>'🛣️','Makan'=>'🍽️','Tiket'=>'🎫','Parkir'=>'🅿️','Lainnya'=>'📦'];
            @endphp
            @foreach($categorySummary as $cat => $amount)
            <div class="card p-3 flex-shrink-0 min-w-[100px] text-center">
                <span class="text-xl">{{ $catIcons[$cat] ?? '💰' }}</span>
                <p class="text-[10px] text-gray-500 mt-1">{{ $cat }}</p>
                <p class="text-xs font-bold text-gray-900">Rp {{ number_format($amount, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif



    {{-- Expense List --}}
    <div>
        <h3 class="font-semibold text-gray-900 text-sm mb-2">Semua Transaksi</h3>
        <div class="space-y-2" id="expense-list">
            @forelse($expenses as $expense)
            <div class="card p-3 flex items-center gap-3 animate-fade-in" id="expense-{{ $expense->id }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $expense->category_color }}">
                    {{ $expense->category_icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm text-gray-900 truncate">{{ $expense->description ?: $expense->category }}</p>
                    <p class="text-[10px] text-gray-500">Dana Kas • {{ $expense->created_at->format('d/m H:i') }}</p>
                </div>
                <div class="text-right flex items-center gap-2">
                    <p class="font-semibold text-sm text-gray-900">Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
                    <button onclick='openEditExpenseModal(@json($expense))' class="text-gray-300 hover:text-emerald-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="deleteExpense({{ $expense->id }})" class="text-gray-300 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <span class="text-4xl">📝</span>
                <p class="text-gray-500 text-sm mt-2">Belum ada pengeluaran</p>
                <p class="text-gray-400 text-xs">Tap tombol + untuk menambah</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Add/Edit Expense Modal --}}
<div id="expense-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-handle"></div>
        <h3 id="expense-modal-title" class="text-lg font-bold text-gray-900 mb-5">💸 Tambah Pengeluaran</h3>

        <form id="expense-form" class="space-y-5">
            <input type="hidden" name="_method" id="expense-method" value="POST">
            <input type="hidden" id="expense-id" value="">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Kategori</label>
                <div class="grid grid-cols-3 gap-2.5">
                    @php
                        $catIcons = ['BBM'=>'⛽','Tol'=>'🛣️','Makan'=>'🍽️','Tiket'=>'🎫','Parkir'=>'🅿️','Lainnya'=>'📦'];
                    @endphp
                    @foreach($categories as $cat)
                    <label class="cursor-pointer">
                        <input type="radio" name="category" value="{{ $cat }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                        <div class="p-2.5 rounded-xl border-2 border-gray-100 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-sm transition-all duration-200 hover:border-gray-200">
                            <span class="text-xl">{{ $catIcons[$cat] }}</span>
                            <p class="text-[10px] font-semibold text-gray-600 mt-1">{{ $cat }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Nominal (Rp)</label>
                <input type="number" name="amount" id="expense-amount" required placeholder="50000">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Keterangan (opsional)</label>
                <input type="text" name="description" id="expense-description" placeholder="Makan siang di Wonosobo...">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider">Foto Bukti (opsional)</label>
                <input type="file" name="receipt_photo" id="expense-photo" accept="image/*" onchange="previewImage(this, 'receipt-preview')">
                <img id="receipt-preview" class="hidden mt-2 w-full h-32 object-cover rounded-xl" alt="Preview">
            </div>

            <button type="submit" id="submit-expense" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 active:scale-[0.98]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Simpan
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openAddExpenseModal() {
    document.getElementById('expense-modal-title').innerText = '💸 Tambah Pengeluaran';
    document.getElementById('expense-form').reset();
    document.getElementById('expense-method').value = 'POST';
    document.getElementById('expense-id').value = '';
    document.getElementById('receipt-preview').classList.add('hidden');
    document.getElementById('receipt-preview').src = '';
    openModal('expense-modal');
}

function openEditExpenseModal(expense) {
    document.getElementById('expense-modal-title').innerText = '✏️ Edit Pengeluaran';
    document.getElementById('expense-method').value = 'PUT';
    document.getElementById('expense-id').value = expense.id;
    
    document.querySelector(`input[name="category"][value="${expense.category}"]`).checked = true;
    document.getElementById('expense-amount').value = expense.amount;
    document.getElementById('expense-description').value = expense.description || '';
    
    const preview = document.getElementById('receipt-preview');
    if (expense.receipt_photo) {
        preview.src = '/storage/' + expense.receipt_photo;
        preview.classList.remove('hidden');
    } else {
        preview.src = '';
        preview.classList.add('hidden');
    }
    
    openModal('expense-modal');
}

document.getElementById('expense-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-expense');
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-spin">⏳</span> Menyimpan...';

    const formData = new FormData(this);
    const method = document.getElementById('expense-method').value;
    const id = document.getElementById('expense-id').value;
    const url = method === 'PUT' ? `/expenses/${id}` : '{{ route("expenses.store") }}';

    try {
        const data = await ajax(url, {
            method: 'POST', // Use POST with _method=PUT in formData
            body: formData,
        });

        showToast('Pengeluaran berhasil disimpan! 💸');
        closeModal('expense-modal');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        const msg = error.data?.message || 'Gagal menyimpan. Coba lagi.';
        showToast(msg, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Simpan';
    }
});

async function deleteExpense(id) {
    const isConfirmed = await customConfirm('Hapus pengeluaran ini?', 'Hapus Pengeluaran');
    if (!isConfirmed) return;

    try {
        await ajax(`/expenses/${id}`, { method: 'DELETE' });
        document.getElementById(`expense-${id}`).remove();
        showToast('Pengeluaran dihapus');
        setTimeout(() => location.reload(), 500);
    } catch (error) {
        showToast('Gagal menghapus', 'error');
    }
}
</script>
@endsection

