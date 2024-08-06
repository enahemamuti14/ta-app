@extends('layouts.tenant')

@section('content')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl">
        <h1 class="text-2xl font-bold mb-4 mt-4 text-center">Detail Pesanan</h1>
        <!-- Menampilkan notifikasi -->
        {{-- @if(session('success'))
            <div class="bg-green-200 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Sukses!</strong>
                <p>{{ session('success') }}</p>
            </div>
        @elseif(session('error'))
            <div class="bg-red-200 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Error!</strong>
                <p>{{ session('error') }}</p>
            </div>
        @endif --}}

        <div class="bg-white shadow rounded-md px-2 py-4 flex-1">
            <table class="w-screen max-w-5xl bg-white text-center items-center">
                <thead>
                <tr class="text-center">
                    <th class="py-2 px-4 border-b text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Order</th>
                    <th class="py-2 px-4 border-b text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                    <th class="py-2 px-4 border-b text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="py-2 px-4 border-b text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    <th class="py-2 px-4 border-b text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="py-2 px-4 border-b text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                <tr class="text-center">
                    <td class="py-2 px-4 border-b whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->format('Y-m-d') }}<br>{{ $item->created_at->format('H:i:s') }}</td>
                    <td class="py-2 px-4 border-b whitespace-nowrap text-sm text-gray-500">{{ $item->menu }}</td>
                    <td class="py-2 px-4 border-b whitespace-nowrap text-sm text-gray-500">{{ $item->qty }}</td>
                    <td class="py-2 px-4 border-b whitespace-nowrap text-sm text-gray-500">{{ $item->note }}</td>
                    <td class="py-2 px-4 border-b whitespace-nowrap text-sm text-gray-500">
                    <p class="bg-gray-500 text-white font-bold py-2 px-2 text-center rounded focus:outline-none focus:shadow-outline">
                        {{ $item->status == 'accepted' ? 'Diterima' : ($item->status == 'rejected' ? 'Ditolak' : 'Menunggu') }}
                    </p>
                    </td>
                    <td class="px-6 py-4 border-b whitespace-nowrap text-sm font-medium text-gray-900">
                    <form action="{{ route('orders.accept', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button class="bg-blue-700 hover:bg-gray-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Terima
                        </button>
                    </form>
                    <form action="{{ route('orders.reject', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Tolak
                        </button>
                    </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">Tidak ada pesanan.</td>
                </tr>
                @endforelse
                </tbody>
            </table>  
        </div>   
    </div>
</div>
@endsection
