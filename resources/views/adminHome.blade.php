@extends('layouts.home')

@section('content4')
<div class="p-4 absolute bottom-0 top-20 left-40">
    <h1 class="text-2xl font-bold mb-4 mt-8 text-center">Admin Dashboard</h1>
    <div class="flex gap-4 container mx-auto w-screen max-w-6xl mt-8">
        <!-- Card Jumlah Transaksi -->
        <div class="flex-1  bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold ">Jumlah Transaksi</h2>
            <div class="text-xl font-bold text-blue-600">
                {{ number_format($totalTransactionsToday) }}
            </div>
            <div class="flex gap-2 mt-3">
                @if ($totalIncomeToday > $totalIncomeYesterday)
                    <img src="{{ asset('img/chart-up.png') }}" alt="Naik">
                    <p>Naik Dari Hasil Kemarin</p>
                @else
                    <img src="{{ asset('img/chart-down.png') }}" alt="Menurun">
                    <p>Menurun Dari Hasil Kemarin</p>
                @endif
            </div>
        </div>
        <!-- Card total pendapatan -->
        <div class="flex-1 w-80 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold">Total Pendapatan</h2>
            <div class="text-xl font-bold text-blue-600">
                {{ number_format($totalIncomeToday, 2) }}
            </div>
            <div class="flex gap-2 mt-3">
                @if ($totalIncomeToday > $totalIncomeYesterday)
                    <img src="{{ asset('img/chart-up.png') }}" alt="Naik">
                    <p>Naik Dari Hasil Kemarin</p>
                @else
                    <img src="{{ asset('img/chart-down.png') }}" alt="Menurun">
                    <p>Menurun Dari Hasil Kemarin</p>
                @endif
            </div>
        </div>
        <!-- Card kasir yg bertugas -->
        <div class="flex-1 w-80 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold">Kasir Yang Bertugas</h2>
            <div class="text-xl font-bold text-blue-600 mt-5">
                @if ($kasir)
                <div class="text-xl font-bold text-blue-600 mt-5">
                    {{ $kasir->name }}
                </div>
                @else
                    <div class="text-xl font-bold text-blue-600 mt-5">
                        Tidak Ada Kasir Bertugas
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{-- tabel transaksi  --}}
        <div class="bg-white shadow rounded-md px-2 py-4  ">
            <h2 class="text-xl font-semibold mb-4 mt-4 ml-4">Data Penjualan Tenant</h2>
            <!-- Tabel Transaksi -->
        <div class="bg-white shadow rounded-md px-2 py-4">
            <table class="min-w-full bg-white text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Tenant ID</th>
                        <th class="py-2 px-4 border-b">Date</th>
                        <th class="py-2 px-4 border-b">Tenant</th>
                        <th class="py-2 px-4 border-b">Total Income</th>
                        <th class="py-2 px-4 border-b">Jumlah Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tenantStatistics as $statistic)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $statistic->tenant_id }}</td>
                        <td class="py-2 px-4 border-b">{{ $date }}</td>
                        <td class="py-2 px-4 border-b">{{ $statistic->tenant_name ?? 'Unknown' }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($statistic->total_income, 2) }}</td>
                        <td class="py-2 px-4 border-b">{{ $statistic->total_orders }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
