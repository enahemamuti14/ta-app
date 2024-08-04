@extends('layouts.kasir')

@section('content')
<div class="absolute bottom-0 top-28 left-44">
    <div class="container mx-auto w-screen max-w-6xl mt-8">
        <h1 class="text-2xl font-bold mb-4">Laporan Pemasukan Harian</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-lg">Tanggal: {{ \Carbon\Carbon::today()->format('d M Y') }}</p>
    
            <!-- Tabel transaksi -->
            <table class="min-w-full divide-y divide-gray-200 mt-4">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tenant</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($transactionDetails as $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->transaction_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->tenant_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->menu_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($detail->created_at)->format('d M Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    
            <div class="mt-4">
                <p class="text-xl font-semibold">Total Pemasukan Hari Ini: Rp. {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
@endsection
