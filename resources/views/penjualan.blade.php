@extends('layouts.home')

@section('content2')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <h1 class="text-2xl font-bold mb-6 text-center">DATA LAPORAN PENJUALAN</h1>
    
    {{-- Form untuk filter --}}
    <div class="bg-white shadow rounded-md px-6 py-4 mb-4 mt-4">
        <form method="GET" action="{{ route('reports.sales') }}">
            <div class="flex items-center space-x-4">
                <div class="flex">
                    <label for="date" class="text-gray-700">Tanggal:</label>
                    <input type="date" id="date" name="date" value="{{ request()->get('date') }}" class="border border-gray-300 rounded ml-4 px-4 py-2">
                </div>
                <div class="flex">
                    <label for="month" class="text-gray-700">Bulan:</label>
                    <input type="month" id="month" name="month" value="{{ request()->get('month') }}" class="border border-gray-300 rounded ml-4 px-4 py-2">
                </div>
                <div class="flex">
                    <label for="year" class="text-gray-700">Tahun:</label>
                    <input type="number" id="year" name="year" value="{{ request()->get('year') }}" class="border border-gray-300 rounded ml-4 px-4 py-2" min="2000" max="{{ date('Y') }}">
                </div>
                <button type="submit" class="bg-blue-500 text-white ml-6 px-4 py-2 rounded">Filter</button>
            </div>
        </form>
    </div>

    
    {{-- tabel transaksi  --}}
    <div class="bg-white shadow rounded-md px-2 py-4 flex-1">
        <table class="w-screen max-w-6xl bg-white text-center">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Tenant ID</th>
                    <th class="py-2 px-4 border-b">Date</th>   
                    <th class="py-2 px-4 border-b">Tenant</th>
                    <th class="py-2 px-4 border-b">Total Income</th>
                    <th class="py-2 px-4 border-b">Jumlah Pemesanan</th>
                    <th class="py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenantStatistics as $statistic)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $statistic->tenant_id }}</td>
                    <td class="py-2 px-4 border-b">{{ $startDate }} - {{ $endDate }}</td>
                    <td class="py-2 px-4 border-b">{{ $statistic->tenant_namatenant ?? 'Unknown' }}</td>
                    <td class="py-2 px-4 border-b">{{ number_format($statistic->total_income, 2) }}</td>
                    <td class="py-2 px-4 border-b">{{ $statistic->total_orders }}</td>
                    <td class="py-4 px-4 border-b flex gap-4">
                        <a href="{{ route('tenant.salesDetails', ['tenant_id' => $statistic->tenant_id]) }}">
                            <button class="bg-blue-500 text-white px-4 py-1 rounded">Lihat</button>
                        </a>
                    </td>
                </tr>
            @endforeach
            
            </tbody>
        </table>
    </div>
</div>
@endsection
