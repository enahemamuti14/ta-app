@extends('layouts.tenant')

@section('content')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl mt-8">
        <h1 class="text-2xl font-bold mb-4 text-center">Daftar Menu Tenant</h1>
        <div class="flex items-center mb-4">
            <!-- Search Bar -->
            <form id="searchForm" method="GET" class="flex items-center flex-1">
                <input type="text" id="searchInput" name="query" placeholder="Cari menu..." class="w-full px-4 py-2 rounded border border-blue-900 text-blue-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-5 py-2 ml-2 bg-blue-900 text-white rounded hover:bg-blue-700">Cari</button>
            </form>
            
            <!-- Tambah Menu Button -->
            <div class="ml-4">
                <a href="{{ Route('createMenu') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Menu</a>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($menus as $menu)
            <div class="menu-card bg-white shadow-md rounded-lg p-4 cursor-pointer text-center">
                <img class="w-full h-32 object-cover" src="{{ asset('img/menucard.jpg') }}" alt="{{ $menu->name }}">
                <h2 class="text-sm font-bold">{{ $menu->name }}</h2>
                <p class="mt-0 text-xs text-gray-600">{{ $menu->description }}</p>
                <p class="mt-2 text-gray-800 font-bold">Harga: Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                
                <!-- Tombol Edit dan Hapus -->
                <div class="flex justify-center mt-4 space-x-2">
                    <!-- Tombol Edit -->
                    <a href="{{ Route('tenants.updatemenu', $menu->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Edit
                    </a>
                    <!-- Tombol Hapus -->
                    <form action="{{ Route('tenants.deleteMenu', $menu->id ) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus menu ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
        
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#searchInput').on('input', function() {
        var query = $(this).val();
        $.ajax({
            url: "{{ route('showmenu') }}",
            type: "GET",
            data: { query: query },
            success: function(data) {
                $('#menuList').html(data);
            }
        });
    });
});
</script>
@endsection