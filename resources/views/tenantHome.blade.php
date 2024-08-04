@extends('layouts.tenant')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <div class="container w-screen max-w-6xl">
        <h1 class="text-2xl font-bold mb-4 text-center">Dashboard Tenant</h1>
        <!-- Tabel Laporan Penjualan Hari Ini -->
        <h2 class="text-xl font-bold mb-4">Laporan Penjualan Hari Ini</h2>
    
        @if(isset($todaySales) && $todaySales->isNotEmpty())
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Tenant</th>
                    <th class="py-2 px-4 border-b text-left">Jumlah Transaksi</th>
                    <th class="py-2 px-4 border-b text-left">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($todaySales as $sale)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $sale->tenant_name }}</td>
                    <td class="py-2 px-4 border-b">{{ $sale->transaction_count }}</td>
                    <td class="py-2 px-4 border-b">{{ number_format($sale->total_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500">Belum ada penjualan hari ini.</p>
        @endif

    </div>
       
    </div>
</div>
@endsection
