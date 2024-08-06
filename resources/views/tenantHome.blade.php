@extends('layouts.tenant')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <div class="container mx-auto w-screen max-w-6xl">
        <h1 class="text-2xl font-extrabold mb-6 text-center">Dashboard Tenant</h1>
    
        <!-- Kontainer Grafik dengan Flexbox -->
        <div class="flex flex-wrap -mx-2 mb-8">
            <!-- Grafik Pemasukan Hari Ini -->
            <div class="w-full md:w-1/3 px-2 mb-4">
                <div class="bg-white p-4 shadow-md rounded h-40">
                    <h2 class="text-xl font-bold mb-4 text-center">Pemasukan Hari Ini</h2>
                    <div id="incomeChart" class="h-full w-full"></div>
                </div>
            </div>

            <!-- Grafik Pesanan Hari Ini -->
            <div class="w-full md:w-1/3 px-2 mb-4">
                <div class="bg-white p-4 shadow-md rounded h-40">
                    <h2 class="text-xl font-bold mb-4 text-center">Pesanan Hari Ini</h2>
                    <div id="orderChart" class="h-full w-full"></div>
                </div>
            </div>

            <!-- Grafik Penjualan per Menu Hari Ini -->
            <div class="w-full md:w-1/3 px-2 mb-4">
                <div class="bg-white p-4 shadow-md rounded h-40">
                    <h2 class="text-xl font-bold mb-4 text-center">Penjualan Menu Hari Ini</h2>
                    <div id="menuChart" class="h-full w-full"></div>
                </div>
            </div>
        </div>

        <!-- Tabel Transaksi Hari Ini -->
        <div class="bg-white p-6 shadow-md rounded">
            <h2 class="text-xl font-bold mb-4 text-center">Tabel Transaksi Hari Ini</h2>
            @if($todaySales->isNotEmpty())
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
                    @foreach($todaySales as $sale)
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
            <p class="text-gray-500">Belum ada transaksi hari ini.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed');
    console.log('Income Data:', @json($incomeData->total_income ?? 0));
    console.log('Order Data:', @json($orderData->total_orders ?? 0));
    console.log('Menu Data:', @json($menuData->pluck('total_sales')));
    console.log('Menu Labels:', @json($menuData->pluck('menu_name')));

    // Grafik Pemasukan Hari Ini
    var incomeOptions = {
        chart: {
            type: 'bar',
            height: '100%'
        },
        series: [{
            name: 'Total Pemasukan',
            data: [{{ $incomeData->total_income ?? 0 }}]
        }],
        xaxis: {
            categories: ['Pemasukan Hari Ini']
        },
        colors: ['#36A2EB'],
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded'
            }
        }
    };

    var incomeChart = new ApexCharts(document.querySelector("#incomeChart"), incomeOptions);
    incomeChart.render();

    // Grafik Pesanan Hari Ini
    var orderOptions = {
        chart: {
            type: 'bar',
            height: '100%'
        },
        series: [{
            name: 'Total Pesanan',
            data: [{{ $orderData->total_orders ?? 0 }}]
        }],
        xaxis: {
            categories: ['Pesanan Hari Ini']
        },
        colors: ['#FF6384'],
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded'
            }
        }
    };

    var orderChart = new ApexCharts(document.querySelector("#orderChart"), orderOptions);
    orderChart.render();

    // Grafik Penjualan per Menu Hari Ini
    var menuLabels = @json($menuData->pluck('menu_name'));
    var menuSales = @json($menuData->pluck('total_sales'));

    var menuOptions = {
        chart: {
            type: 'bar',
            height: '100%'
        },
        series: [{
            name: 'Total Penjualan',
            data: menuSales
        }],
        xaxis: {
            categories: menuLabels
        },
        colors: ['#4CAF50'],
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded'
            }
        }
    };

    var menuChart = new ApexCharts(document.querySelector("#menuChart"), menuOptions);
    menuChart.render();
});
</script>
@endsection
