@extends('layouts.home')

@section('content2')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <h1 class="text-2xl font-bold mb-6 text-center">DATA LAPORAN PENJUALAN</h1>
    
    {{-- Form untuk filter --}}
    <form method="GET" action="{{ route('reports.sales') }}" class="mb-4">
        <label for="filter" class="mr-2">Filter by:</label>
        <select name="filter" id="filter" onchange="this.form.submit()" class="border rounded p-2">
            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Yearly</option>
        </select>
    </form>
    
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
                    <td class="py-2 px-4 border-b">{{ $date }}</td>
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
