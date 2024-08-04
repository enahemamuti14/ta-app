@extends('layouts.home')

@section('content3')
<div class="absolute bottom-0 top-28 left-44 ">
    <div class="w-screen max-w-6xl">
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <!-- Tombol Kembali -->
        <a href="{{ route('tenants.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Kembali</a>

        <form action="{{ route('tenants.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label for="tanggalmulaisewa" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai Sewa :</label>
                <input type="date" name="tanggalmulaisewa" id="tanggalmulaisewa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggalmulaisewa') border-red-500 @enderror" value="{{ old('tanggalmulaisewa') }}">
                @error('tanggalmulaisewa')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>            

            <div class="mb-4">
                <label for="tanggalberakhirsewa" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Berakhir Sewa :</label>
                <input type="date" name="tanggalberakhirsewa" id="tanggalberakhirsewa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggalberakhirsewa') border-red-500 @enderror" value="{{ old('tanggalberakhirsewa') }}">
                @error('tanggalberakhirsewa')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            

            <div class="mb-4">
                <label for="namatenant" class="block text-gray-700 text-sm font-bold mb-2">Nama Tenant :</label>
                <input type="namatenant" name="namatenant" id="namatenant" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('namatenant') border-red-500 @enderror" value="{{ old('namatenant') }}">
                @error('namatenant')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="statussewa" class="block text-gray-700 text-sm font-bold mb-2">Status Sewa :</label>
                <input type="statussewa" name="statussewa" id="statussewa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('statussewa') border-red-500 @enderror" value="{{ old('statussewa') }}">
                @error('statussewa')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="kontak" class="block text-gray-700 text-sm font-bold mb-2">Kontak :</label>
                <input type="kontak" name="kontak" id="kontak" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kontak') border-red-500 @enderror" value="{{ old('kontak') }}">
                @error('kontak')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-center">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Tambah Tenant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
