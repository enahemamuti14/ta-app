@extends('layouts.home')

@section('content3')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl">
        <!-- Tombol Kembali -->
        <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-4 py-2 mb-4 bg-gray-500 text-white font-bold rounded hover:bg-gray-600">
            &larr; Kembali ke Daftar Tenant
        </a>
        <h1 class="text-2xl font-bold mb-4">Detail Tenant: {{ $tenant->namatenant }}</h1>

        <div class="my-4 gap-4">
            <!-- Tambahkan aksi jika diperlukan, seperti Edit dan Delete -->
            <a href="{{ route('tenants.edit', $tenant->id) }}" class=" bg-blue-500 text-white px-4 py-2 rounded">Edit Tenant</a>
        </div>

        <!-- Detail Tenant -->
        <div class="bg-white shadow-md rounded p-4 mb-4">
            <p><strong>Tanggal Mulai Sewa:</strong> {{ $tenant->tanggalmulaisewa->format('d-m-Y') }}</p>
            <p><strong>Tanggal Berakhir Sewa:</strong> {{ $tenant->tanggalberakhirsewa->format('d-m-Y') }}</p>
            <p><strong>Status Sewa:</strong> {{ $tenant->statussewa }}</p>
            <p><strong>Kontak:</strong> {{ $tenant->kontak }}</p>
        </div>

        <!-- Horizontal Navigation Bar -->
        <div class="mb-4">
            <ul class="flex space-x-4">
                <li>
                    <a href="{{ route('tenants.show', $tenant->id) }}" class="px-4 py-2 hover:text-blue-600 {{ request()->routeIs('tenants.show') ? 'border-b-2 border-blue-600 font-bold' : '' }}">
                        Menu
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenants.transactions', $tenant->id) }}" class="px-4 py-2 hover:text-blue-600 {{ request()->routeIs('tenants.transactions') ? 'border-b-2 border-blue-600 font-bold' : '' }}">
                        Data Transaksi
                    </a>
                </li>
            </ul>
        </div>

         <!-- Content Area -->
         <div class="bg-white shadow-md rounded-lg p-4">
            @if(request()->routeIs('tenants.show'))
                <!-- Display Menu -->
                <h2 class="text-xl font-bold mb-6 mt-4">Menu untuk Tenant {{ $tenant->namatenant }}</h2>
                <!-- Button to go to Add Menu Form -->
                <div class="mt-4 flex mb-4">
                    <a href="{{ route('tenants.createMenu', $tenant->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Menu</a>
                </div>
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Nama Menu</th>
                            <th class="py-2 px-4 border-b">Harga</th>
                            <th class="py-2 px-4 border-b">Deskripsi</th>
                            <th class="py-2 px-4 border-b ">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenant->menus as $menu)
                            <tr class=" text-center">
                                <td class="py-2 px-4 border-b">{{ $menu->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $menu->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $menu->price }}</td>
                                <td class="py-2 px-4 border-b">{{ $menu->description }}</td>
                                <td class="py-4 px-4 border-b flex gap-4 items-center justify-center">
                                    <a href="{{ route('tenants.editMenu', [$tenant->id, $menu->id]) }}" class="bg-blue-500 text-white px-4 py-1 rounded">Edit</a>
                                    <form action="{{ route('tenants.destroyMenu', [$tenant->id, $menu->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            @elseif(request()->routeIs('tenants.transactions'))
                <!-- Display Transactions -->
                <h2 class="text-xl font-bold mb-4">Data Transaksi untuk Tenant {{ $tenant->namatenant }}</h2>
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Jumlah</th>
                            <th class="py-2 px-4 border-b">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td class="py-2 px-4 border-b text-center">{{ $transaction->id }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ number_format($transaction->amount, 2) }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $transaction->date}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-2 px-4 border-b text-center">Tidak ada data transaksi untuk tenant ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
