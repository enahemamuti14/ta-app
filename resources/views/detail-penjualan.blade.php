<!-- resources/views/tenants/salesDetails.blade.php -->
@extends('layouts.home')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <div class="container mx-auto w-screen max-w-6xl mt-8">
        <a href="{{ route('reports.sales') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
            Kembali
        </a>
        <h2 class="text-2xl font-semibold mb-6 text-center">Detail Penjualan untuk Tenant {{ $tenant->name }}</h2>
        <div class="bg-white shadow rounded-md px-2 py-4 flex-1">
            <table class="min-w-full divide-y divide-gray-200 mt-4 text-center">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tenant</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salesDetails as $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->transaction_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->tenant_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->menu_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($detail->subtotal, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
