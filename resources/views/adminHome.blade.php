@extends('layouts.home')

@section('content4')
<div class="p-4 absolute bottom-0 top-20 left-40">
    <h1 class="text-2xl font-bold mb-4 mt-8 text-center">Admin Dashboard</h1>
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
        <!-- Card Kasir Yang Bertugas -->
        {{-- <div class="flex-1 w-80 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold">Kasir Yang Bertugas</h2>
            <div class="text-xl font-bold text-blue-600 mt-5">
                @if ($kasir)
                    <div class="text-xl font-bold text-blue-600 mt-5">{{ $kasir->name }}</div>
                @else
                    <div class="text-xl font-bold text-blue-600 mt-5">Tidak Ada Kasir Bertugas</div>
                @endif
            </div>
        </div> --}}
    </div>
    <div class="mt-4">
        <div class="bg-white shadow rounded-md px-2 py-4">
            <h2 class="text-xl font-semibold mb-4 mt-4 ml-4">Data Penjualan Tenant</h2>
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
