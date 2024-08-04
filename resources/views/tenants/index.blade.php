@extends('layouts.home')

@section('content3')
<div class="absolute bottom-0 top-28 left-44 mt-5">
<h1 class="text-2xl font-bold mb-8 text-center">DATA DAFTAR TENANT</h1>
    <div class="flex space-x-0 mb-4">
    <!-- Pencarian Data User -->
       <div class="w-full flex-1">
           <input type="text" id="searchUser" placeholder="Search tenant.." class="w-11/12 px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
       </div>
       <a href="{{ route('tenants.create') }}">
            <button class="relative z-10 bg-blue-500 text-white px-4 py-2 rounded -ml-14">Tambah Tenant</button>
        </a>
        <a href="{{ route('tenant.link.form') }}">
            <button class="relative z-10 bg-blue-500 text-white px-4 py-2 rounded ml-4">TenantUser</button>
        </a>
   </div>
 {{-- tabel transaksi  --}}
 <div class="bg-white shadow rounded-md px-2 py-4 flex-1">
    <table class="w-screen max-w-6xl bg-white text-center">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Tenant ID</th>
                <th class="py-2 px-4 border-b">Tanggal Mulai Sewa</th>
                <th class="py-2 px-4 border-b">Tanggal Berakhir Sewa</th>
                <th class="py-2 px-4 border-b">Nama Tenant</th>
                <th class="py-2 px-4 border-b">Status Sewa</th>
                <th class="py-2 px-4 border-b">Kontak</th>
                <th class="py-2 px-4 border-b">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tenants as $tenant) <!-- Iterasi elemen dari koleksi -->
                <tr>
                    <td class="py-2 px-4 border-b">{{ $tenant->id }}</td>
                    <td class="py-2 px-4 border-b">{{ $tenant->tanggalmulaisewa->format('d-m-Y') }}</td>
                    <td class="py-2 px-4 border-b">{{ $tenant->tanggalberakhirsewa->format('d-m-Y') }}</td>
                    <td class="py-2 px-4 border-b">{{ $tenant->namatenant }}</td>
                    <td class="py-2 px-4 border-b">{{ $tenant->statussewa }}</td>
                    <td class="py-2 px-4 border-b">{{ $tenant->kontak }}</td>
                    <td class="py-2 px-4 border-b">
                        <!-- Tambahkan aksi jika diperlukan, seperti Edit dan Delete -->
                        <a href="{{ route('tenants.show', $tenant->id) }}" class="bg-yellow-500 text-white px-4 py-1 rounded">Lihat</a>
                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@endsection