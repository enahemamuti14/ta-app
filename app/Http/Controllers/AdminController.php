<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
//     public function index(Request $request)
//     {
//         $user = Auth::user();
//         $date = $request->input('date', date('Y-m-d')); // Tanggal default hari ini jika tidak diberikan
//         $yesterday = Carbon::parse($date)->subDay()->format('Y-m-d');

//         $kasir = DB::table('users')
//         ->where('role', 2) // 2 untuk kasir
//         ->first();

//         // Hitung pendapatan hari ini
//         $totalIncomeToday = DB::table('transaction_details')
//             ->whereDate('created_at', $date)
//             ->sum('subtotal');
        
//         // Hitung total transaksi hari ini
//         $totalTransactionsToday = DB::table('transaction_details')
//             ->whereDate('created_at', $date)
//             ->sum('quantity');

//         // Hitung pendapatan kemarin
//         $totalIncomeYesterday = DB::table('transaction_details')
//             ->whereDate('created_at', $yesterday)
//             ->sum('subtotal');

//         // Hitung pendapatan kemarin
//         $totalTransactionYesterday = DB::table('transaction_details')
//             ->whereDate('created_at', $yesterday)
//             ->sum('quantity');

//         $tenantStatistics = DB::table('transactions')
//             ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
//             ->join('tenant', 'transactions.tenant_id', '=', 'tenant.id') // Pastikan join dengan tabel tenant
//             ->select(
//                 'transactions.tenant_id', 
//                 'tenant.namatenant as tenant_name', 
//                 DB::raw('SUM(transaction_details.subtotal) as total_income'), 
//                 DB::raw('SUM(transaction_details.quantity) as total_orders')
//             )
//             ->whereDate('transactions.created_at', $date)
//             ->groupBy('transactions.tenant_id', 'tenant.namatenant')
//             ->get();

//         return view('adminHome', compact('tenantStatistics', 'date', 'tenantStatistics','user', 'totalIncomeToday', 'totalIncomeYesterday', 'totalTransactionsToday', 'totalTransactionYesterday', 'kasir'));
//     }
// }
public function index(Request $request)
{
    $user = Auth::user();
    $date = $request->input('date', date('Y-m-d'));
    $oneMonthAgo = Carbon::parse($date)->subMonth()->format('Y-m-d');

    $kasir = DB::table('users')
        ->where('role', 2)
        ->first();

    $dailyIncome = DB::table('transaction_details')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(subtotal) as total_income')
        )
        ->whereBetween('created_at', [$oneMonthAgo, $date])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

    $dailyTransactions = DB::table('transaction_details')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(quantity) as total_transactions')
        )
        ->whereBetween('created_at', [$oneMonthAgo, $date])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

    $dates = [];
    $incomes = [];
    $transactions = [];

    foreach ($dailyIncome as $income) {
        $dates[] = $income->date;
        $incomes[] = $income->total_income;
    }

    foreach ($dailyTransactions as $transaction) {
        $transactions[] = $transaction->total_transactions;
    }

    $tenantStatistics = DB::table('transactions')
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->join('tenant', 'transactions.tenant_id', '=', 'tenant.id')
        ->select(
            'transactions.tenant_id',
            'tenant.namatenant as tenant_name',
            DB::raw('SUM(transaction_details.subtotal) as total_income'),
            DB::raw('SUM(transaction_details.quantity) as total_orders')
        )
        ->whereBetween('transactions.created_at', [$oneMonthAgo, $date])
        ->groupBy('transactions.tenant_id', 'tenant.namatenant')
        ->get();

    // Debug data
    // dd(compact('tenantStatistics', 'date', 'user', 'kasir', 'dates', 'incomes', 'transactions'));

    return view('adminHome', compact('tenantStatistics', 'date', 'user', 'kasir', 'dates', 'incomes', 'transactions'));
}
}