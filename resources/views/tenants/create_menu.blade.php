@extends('layouts.home')

@section('content3')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl">
        <a href="{{ route('tenants.show', $tenant->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Kembali</a>
        <h1 class="text-2xl font-bold mb-4">Tambah Menu untuk Tenant: {{ $tenant->namatenant }}</h1>
        
        <!-- Formulir Tambah Menu -->
        <div class="bg-white shadow-md rounded p-4">
            <form action="{{ route('tenants.storeMenu', $tenant->id) }}" method="POST" class="">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Menu</label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2 ">Harga</label>
                    <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
