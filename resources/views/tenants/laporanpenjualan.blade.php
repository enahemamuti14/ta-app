@extends('layouts.tenant')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <h2 class="text-2xl font-bold mb-6 text-center">Laporan Penjualan</h2>
    <div class="flex gap-4 container mx-auto w-screen max-w-6xl mt-8">
        <!-- Card Jumlah Transaksi -->
        <div class="flex-1 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold">Jumlah Transaksi</h2>
            <div class="text-xl font-bold text-blue-600">
                <canvas id="transactionChart" width="400" height="160"></canvas>
            </div>
        </div>
        <!-- Card Total Pendapatan -->
        <div class="flex-1 w-80 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold">Total Pendapatan</h2>
            <div class="text-xl font-bold text-blue-600">
                <canvas id="incomeChart" width="400" height="160"></canvas>
            </div>
        </div>
    </div>
        <!-- Filter Form -->
        <div class="bg-white shadow rounded-md px-6 py-4 mb-4 mt-4">
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctxIncome = document.getElementById('incomeChart').getContext('2d');
        var ctxTransaction = document.getElementById('transactionChart').getContext('2d');

        var dates = @json($dates);
        var incomes = @json($incomes);
        var transactions = @json($transactions);

        console.log('Dates:', dates);
        console.log('Incomes:', incomes);
        console.log('Transactions:', transactions);

        // Grafik pendapatan
        new Chart(ctxIncome, {
            type: 'bar', // Mengubah tipe grafik menjadi bar
            data: {
                labels: dates,
                datasets: [{
                    label: 'Pendapatan',
                    data: incomes,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'category',
                        labels: dates,
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Pendapatan'
                        }
                    }
                }
            }
        });

        // Grafik total transaksi
        new Chart(ctxTransaction, {
            type: 'bar', // Mengubah tipe grafik menjadi bar
            data: {
                labels: dates,
                datasets: [{
                    label: 'Total Transaksi',
                    data: transactions,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        type: 'category',
                        labels: dates,
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
