@extends('layouts.tenant')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <div class="container mx-auto w-screen max-w-6xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Laporan Penjualan</h2>
        
        <!-- Filter Form -->
        <div class="bg-white shadow rounded-md px-6 py-4 mb-4">
            <form method="GET" action="{{ route('tenants.salesDetails') }}">
                <div class="flex items-center space-x-4">
                    <div class="flex ">
                        <label for="date" class="text-gray-700">Tanggal:</label>
                        <input type="date" id="date" name="date" value="{{ request()->get('date') }}" class="border border-gray-300 rounded ml-4 px-4 py-2">
                    </div>
                    <div class="flex">
                        <label for="month" class="text-gray-700">Bulan:</label>
                        <input type="month" id="month" name="month" value="{{ request()->get('month') }}" class="border border-gray-300 rounded ml-4  px-4 py-2">
                    </div>
                    <div class="flex">
                        <label for="year" class="text-gray-700">Tahun:</label>
                        <input type="number" id="year" name="year" value="{{ request()->get('year') }}" class="border border-gray-300 rounded ml-4 px-4 py-2" min="2000" max="{{ date('Y') }}">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white ml-6 px-4 py-2 rounded">Filter</button>
                </div>
            </form>
        </div>
        
        <div class="bg-white shadow rounded-md px-2 py-4 flex-1">
            @if($salesDetails->isNotEmpty())
            <!-- Tabel Transaksi -->
            <div class="p-4">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b text-center">Tanggal</th>
                            <th class="py-3 px-4 border-b text-center">ID Transaksi</th>
                            <th class="py-3 px-4 border-b text-center">Menu</th>
                            <th class="py-3 px-4 border-b text-center">Jumlah</th>
                            <th class="py-3 px-4 border-b text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesDetails as $sale)
                        <tr>
                            <td class="py-2 px-4 border-b text-center">{{ $sale->date }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $sale->transaction_id }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $sale->menu_name }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $sale->quantity }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ number_format($sale->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="text-gray-500">Belum ada transaksi untuk periode yang dipilih.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
