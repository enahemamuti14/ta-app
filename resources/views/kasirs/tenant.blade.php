@extends('layouts.kasir')

@section('content')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl mt-8">    
        <h1 class="text-3xl font-bold mb-7 text-center">Daftar Tenant</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($tenants as $tenant)
            <div class="tenant-card bg-white shadow-md rounded-lg p-4 cursor-pointer text-center">
                <!-- Gambar Tenant -->
                <img src="{{ asset('img/tenantcard.jpg') }}" alt="{{ $tenant->namatenant }}" class="w-full h-40 object-cover rounded-lg mb-6">
                <!-- Nama Tenant -->
                <h2 class="text-xl font-bold mb-4">{{ $tenant->namatenant }}</h2>
                <!-- Tombol Lihat Menu -->
                <a href="{{ route('tenant.showmenu', ['id' => $tenant->id]) }}" class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                    Lihat Menu
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
