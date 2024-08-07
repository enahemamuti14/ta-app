@extends('layouts.kasir')

@section('content')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl mt-8">
    <a href="{{ route('menus.index') }}" class="text-blue-500 hover:text-blue-700 mb-4 inline-block">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Menu
    </a>
    <h1 class="text-2xl font-bold mb-4 text-center">Detail Pesanan</h1>

    <!-- Menampilkan notifikasi -->
<!-- Tampilkan Pesan Sukses atau Error -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<!-- Menampilkan notifikasi dari session -->
@if(isset($notifications) && !empty($notifications))
    @foreach($notifications as $notification)
        <div class="bg-{{ $notification['status'] == 'success' ? 'green' : 'red' }}-200 border border-{{ $notification['status'] == 'success' ? 'green' : 'red' }}-400 text-{{ $notification['status'] == 'success' ? 'green' : 'red' }}-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">{{ $notification['status'] == 'success' ? 'Sukses!' : 'Error!' }}</strong>
            <p>{{ $notification['message'] }}</p>
        </div>
    @endforeach
@endif


    <div class="bg-white shadow-md rounded-lg p-4">
        @forelse($items as $item)
            <div class="mb-4 border-b border-gray-300 pb-2">
                <p class="font-bold">Menu: {{ $item['menuName'] }}</p>
                <p class="text-gray-600">Tenant: {{ $item['tenantName'] }}</p>
                <p class="text-gray-600">Harga: Rp {{ number_format($item['menuPrice'], 0, ',', '.') }}</p>
                <p class="text-gray-600">Jumlah: {{ $item['quantity'] }}</p>
                <p class="text-gray-600">Subtotal: Rp {{ number_format($item['menuPrice'] * $item['quantity'], 0, ',', '.') }}</p>
                <p class="text-gray-600">Catatan: {{ $item['notes'] }}</p> <!-- Tampilkan catatan -->
            </div>
        @empty
            <p class="text-center">Tidak ada pesanan.</p>
        @endforelse
        <div class="mt-4">
            <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
            <select id="payment_method" name="payment_method" class="block shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="cash">Tunai</option>
                <!-- Tambahkan opsi lain jika diperlukan -->
            </select>
        </div>
        <div class="mt-4">
            <label for="amount_given" class="block text-sm font-medium text-gray-700">Jumlah Uang yang Diberikan</label>
            <input type="number" id="amount_given" name="amount_given" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" step="0.01" min="0" required>
        </div>
        <div class="mt-4 font-bold">
            Total: Rp {{ number_format(array_sum(array_map(fn($item) => $item['menuPrice'] * $item['quantity'], $items)), 0, ',', '.') }}
        </div>
        <div class="mt-4">
            <p class="font-bold">Kembalian: <span id="change_amount">Rp 0</span></p>
        </div>
    </div>
    <div class="flex ">
        <form action="{{ route('transactions.tempStore') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="tenant_id" value="{{ $tenantId }}">
            <input type="hidden" name="order_data" value="{{ json_encode($items) }}">
            <input type="hidden" name="payment_method" id="hidden_payment_method_tempStore">
            <input type="hidden" name="amount_given" id="hidden_amount_given_tempStore">
            <button id="validate-order" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">
                Validasi Pesanan
            </button>
        </form>         
    
        <form action="{{ route('transactions.store') }}" method="POST" class="mt-4 ml-4">
            @csrf
            <input type="hidden" name="tenant_id" value="{{ $tenantId }}">
            <input type="hidden" name="order_data" value="{{ json_encode($items) }}">
            <input type="hidden" name="payment_method" id="hidden_payment_method_store">
            <input type="hidden" name="amount_given" id="hidden_amount_given_store">
            <button type="validate-order" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan Pesanan
            </button>
        </form>  
    
    </div>
    
    <script>
        document.getElementById('amount_given').addEventListener('input', function () {
            const total = parseFloat(document.querySelector('.mt-4.font-bold').textContent.replace('Total: Rp ', '').replace(/\./g, '').replace(',', '.'));
            const amountGiven = parseFloat(this.value) || 0;
            const change = amountGiven - total;
            document.getElementById('change_amount').textContent = 'Rp ' + (change >= 0 ? change.toFixed(2).replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,') : '0');
            
            // Update hidden fields
            document.getElementById('hidden_payment_method_tempStore').value = document.getElementById('payment_method').value;
            document.getElementById('hidden_amount_given_tempStore').value = amountGiven.toFixed(2);

            document.getElementById('hidden_payment_method_store').value = document.getElementById('payment_method').value;
            document.getElementById('hidden_amount_given_store').value = amountGiven.toFixed(2);
        });
    </script>
</div>
@endsection
