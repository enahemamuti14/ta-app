@extends('layouts.kasir')

@section('content')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl mt-8">
        <div class="flex">
            <!-- Kolom Daftar Menu -->
            <div class="w-2/3 pr-4">
                <h1 class="text-2xl font-bold mb-4 text-center">Daftar Menu Tenant</h1>
                
                <!-- Form Pencarian Menu -->
                <form action="{{ route('menus.search') }}" method="GET" class="mb-4 flex">
                    <input type="text" id="search" name="query" placeholder="Cari menu..." class="w-10/12 px-4 py-2 rounded border border-blue-900 text-blue-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="px-5 py-2 ml-5 bg-blue-900 text-white rounded hover:bg-blue-700">Cari</button>
                </form>

                <!-- Filter Menu dan Tenant -->
                <div class="flex mb-4">
                    <div class="w-1/2 pr-2">
                        <select id="menu-filter" class="w-full p-2 border rounded">
                            <option value="">Filter Menu</option>
                            <!-- Isi dengan opsi filter menu secara dinamis -->
                        </select>
                    </div>
                    <div class="w-1/2 pl-2">
                        <select id="tenant-filter" class="w-full p-2 border rounded">
                            <option value="">Filter Tenant</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->namatenant }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Daftar Menu -->
                <div class="p-4">
                    <div class="flex flex-wrap gap-4 mb-8">
                        @foreach($menus as $menu)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden w-56 menu-card cursor-pointer" data-menu-id="{{ $menu->id }}" data-tenant-id="{{ $menu->tenant_id }}" data-menu-price="{{ $menu->price }}" data-menu-name="{{ $menu->name }}" data-tenant-name="{{ $menu->tenant->namatenant }}">
                            <img class="w-full h-32 object-cover" src="{{ asset('img/menucard.jpg') }}" alt="{{ $menu->name }}">
                            <div class="p-4 text-center">
                                <h3 class="text-sm font-bold">{{ $menu->name }}</h3>
                                <p class="mt-0 text-xs text-gray-600">{{ Str::limit($menu->description, 50) }}</p>
                                <p class="mt-2 text-gray-800 font-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Detail Transaksi -->
            <div class="w-1/3 pl-4">
                <h1 class="text-2xl font-bold mb-4 text-center">Detail Transaksi</h1>
                <div id="transaction-details" class="bg-white shadow-md rounded-lg p-4">
                    <!-- Detail transaksi akan dimuat di sini -->
                </div>
                <button id="continue-payment" class="mt-4 w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Lanjutkan Pembayaran</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let transactionItems = [];

        document.querySelectorAll('.menu-card').forEach(card => {
            card.addEventListener('click', function() {
                const menuId = this.dataset.menuId;
                const tenantId = this.dataset.tenantId;
                const menuPrice = this.dataset.menuPrice;
                const menuName = this.dataset.menuName;
                const tenantName = this.dataset.tenantName;

                const existingItem = transactionItems.find(item => item.menuId === menuId);
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    transactionItems.push({
                        menuId: menuId,
                        tenantId: tenantId,
                        menuPrice: menuPrice,
                        menuName: menuName,
                        tenantName: tenantName,
                        quantity: 1,
                        notes: ''
                    });
                }

                updateTransactionDetails();
            });
        });

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

            const totalAmountElement = document.createElement('div');
            totalAmountElement.classList.add('mt-4', 'font-bold');
            totalAmountElement.innerHTML = `Total: Rp ${totalAmount.toLocaleString('id-ID')}`;
            transactionDetails.appendChild(totalAmountElement);

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

        document.getElementById('continue-payment').addEventListener('click', function() {
            if (transactionItems.length === 0) {
                alert('Belum ada menu yang dipilih.');
                return;
            }

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
                    window.location.href = "{{ route('payment-detail') }}";
                } else {
                    alert('Gagal menyimpan transaksi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        });

        // Filter Menu
        document.getElementById('search').addEventListener('input', function() {
            filterMenu();
        });

        document.getElementById('menu-filter').addEventListener('change', function() {
            filterMenu();
        });

        document.getElementById('tenant-filter').addEventListener('change', function() {
            filterMenu();
        });

        function filterMenu() {
            const searchQuery = document.getElementById('search').value.toLowerCase();
            const menuFilter = document.getElementById('menu-filter').value;
            const tenantFilter = document.getElementById('tenant-filter').value;

            document.querySelectorAll('.menu-card').forEach(card => {
                const menuName = card.dataset.menuName.toLowerCase();
                const tenantName = card.dataset.tenantName.toLowerCase();

                if (
                    (searchQuery === '' || menuName.includes(searchQuery)) &&
                    (menuFilter === '' || card.dataset.menuCategory === menuFilter) &&
                    (tenantFilter === '' || card.dataset.tenantId === tenantFilter)
                ) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    });
    </script>
</div>
@endsection
