@extends('layouts.kasir')

@section('content')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl mt-8">
        <h1 class="text-2xl font-bold mb-4 text-center">Edit Pesanan</h1>
        <div class="bg-white shadow-md rounded-lg p-4">
            <div id="transaction-details">
                @forelse($items as $index => $item)
                    <div class="mb-4 border-b border-gray-300 pb-2">
                        <p class="font-bold">Menu: {{ $item['menuName'] }}</p>
                        <p class="text-gray-600">Tenant: {{ $item['tenantName'] }}</p>
                        <p class="text-gray-600">Harga: Rp {{ number_format($item['menuPrice'], 0, ',', '.') }}</p>
                        <p class="text-gray-600">Subtotal: Rp {{ number_format($item['menuPrice'] * $item['quantity'], 0, ',', '.') }}</p>
                        <div class="flex items-center">
                            <button class="bg-gray-300 hover:bg-gray-500 text-black font-bold py-1 px-2 rounded decrease-quantity" data-index="{{ $index }}">-</button>
                            <span class="mx-2">{{ $item['quantity'] }}</span>
                            <button class="bg-gray-300 hover:bg-gray-500 text-black font-bold py-1 px-2 rounded increase-quantity" data-index="{{ $index }}">+</button>
                        </div>
                        <textarea class="mt-2 w-full border rounded p-2" placeholder="Catatan untuk item ini" data-index="{{ $index }}">{{ $item['notes'] }}</textarea>
                    </div>
                @empty
                    <p class="text-center">Tidak ada pesanan.</p>
                @endforelse
            </div>
            <div class="mt-4 font-bold" id="total-amount">
                Total: Rp {{ number_format(array_sum(array_map(fn($item) => $item['menuPrice'] * $item['quantity'], $items)), 0, ',', '.') }}
            </div>
        </div>
        <a href="#" id="save-changes" class="mt-4 w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan Perubahan</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let transactionItems = @json($items);

    function updateTransactionDetails() {
        const transactionDetails = document.getElementById('transaction-details');
        transactionDetails.innerHTML = '';

        let totalAmount = 0;

        transactionItems.forEach((item, index) => {
            const newItem = document.createElement('div');
            newItem.classList.add('mb-4', 'border-b', 'border-gray-300', 'pb-2');
            newItem.innerHTML = `
                <p class="font-bold">Menu: ${item.menuName}</p>
                <p class="text-gray-600">Tenant: ${item.tenantName}</p>
                <p class="text-gray-600">Harga: Rp ${parseInt(item.menuPrice).toLocaleString('id-ID')}</p>
                <p class="text-gray-600">Subtotal: Rp ${(item.menuPrice * item.quantity).toLocaleString('id-ID')}</p>
                <div class="flex items-center">
                    <button class="bg-gray-300 hover:bg-gray-500 text-black font-bold py-1 px-2 rounded decrease-quantity" data-index="${index}">-</button>
                    <span class="mx-2">${item.quantity}</span>
                    <button class="bg-gray-300 hover:bg-gray-500 text-black font-bold py-1 px-2 rounded increase-quantity" data-index="${index}">+</button>
                </div>
                <textarea class="mt-2 w-full border rounded p-2" placeholder="Catatan untuk item ini" data-index="${index}">${item.notes}</textarea>
            `;
            transactionDetails.appendChild(newItem);

            totalAmount += item.menuPrice * item.quantity;
        });

        const totalAmountElement = document.getElementById('total-amount');
        totalAmountElement.innerHTML = `Total: Rp ${totalAmount.toLocaleString('id-ID')}`;

        document.querySelectorAll('.decrease-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.dataset.index;
                if (transactionItems[index].quantity > 1) {
                    transactionItems[index].quantity -= 1;
                } else {
                    transactionItems.splice(index, 1);
                }
                updateTransactionDetails();
            });
        });

        document.querySelectorAll('.increase-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.dataset.index;
                transactionItems[index].quantity += 1;
                updateTransactionDetails();
            });
        });

        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                const index = this.dataset.index;
                transactionItems[index].notes = this.value;
            });
        });
    }

    updateTransactionDetails();

    document.getElementById('save-changes').addEventListener('click', function(event) {
        event.preventDefault();
        fetch('/save-transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ items: transactionItems })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route('kasir.index') }}";
            } else {
                alert('Gagal menyimpan perubahan.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    });
});
</script>
@endsection
