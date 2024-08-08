@extends('layouts.tenant')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <div class="container mx-auto w-screen max-w-6xl">
        <h1 class="text-3xl font-extrabold mb-6 text-center">Dashboard Tenant</h1>
        <main class="flex flex-wrap -mx-2 mb-8">
            <div class="w-full md:w-1/3 px-2 mb-4">
                <div class="bg-white p-6 shadow-md rounded">
                    <h2 class="text-xl font-bold mb-4">Grafik Pemasukan</h2>
                    <canvas id="incomeChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="w-full md:w-1/3 px-2 mb-4">
                <div class="bg-white p-6 shadow-md rounded">
                    <h2 class="text-xl font-bold mb-4">Grafik Pesanan</h2>
                    <canvas id="orderChart" width="400" height="200"></canvas>    
                </div>
            </div>
            <div class="w-full md:w-1/3 px-2 mb-4">
                <div class="bg-white p-6 shadow-md rounded">
                    <h2 class="text-xl font-bold mb-4">Grafik Pemasukan per Menu</h2>
                    <canvas id="menuChart" width="400" height="200"></canvas>
                </div>
            </div>
        </main>
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

<script>
    // Grafik Pemasukan
    const incomeData = @json($incomeData);
    const incomeLabels = incomeData.map(item => item.date);
    const incomeValues = incomeData.map(item => item.total_income);

    const ctxIncome = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctxIncome, {
        type: 'bar',
        data: {
            labels: incomeLabels,
            datasets: [{
                label: 'Pemasukan',
                data: incomeValues,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Pesanan
    const orderData = @json($orderData);
    const orderLabels = orderData.map(item => item.date);
    const orderValues = orderData.map(item => item.total_orders);

    const ctxOrder = document.getElementById('orderChart').getContext('2d');
    new Chart(ctxOrder, {
        type: 'bar',
        data: {
            labels: orderLabels,
            datasets: [{
                label: 'Pesanan',
                data: orderValues,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Pemasukan per Menu
    const menuData = @json($menuData);
    const menuLabels = menuData.map(item => item.menu_name);
    const menuValues = menuData.map(item => item.total_sales);

    const ctxMenu = document.getElementById('menuChart').getContext('2d');
    new Chart(ctxMenu, {
        type: 'bar',
        data: {
            labels: menuLabels,
            datasets: [{
                label: 'Pemasukan per Menu',
                data: menuValues,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
