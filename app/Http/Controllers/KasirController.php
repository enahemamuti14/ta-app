<?php

// app/Http/Controllers/KasirController.php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionDetail; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Mengambil pengguna yang sedang login
        $tenants = Tenant::with('menus')->get(); // Muat tenant beserta menu
        $transactions = Transaction::latest()->take(5)->get(); // Muat 5 transaksi terbaru

        return view('kasirs.index', compact('tenants', 'transactions','user'));
    }

    public function show($id)
    {
        $tenant = Tenant::with('menus')->findOrFail($id);
        return view('kasirs.show', compact('tenant'));
    }
    public function dailyReport()
    {
        $user = Auth::user();
        // Ambil transaksi detail untuk hari ini
        $today = Carbon::today();
        $transactionDetails = TransactionDetail::whereDate('created_at', $today->toDateString())->get();
        
        // Hitung total pemasukan hari ini
        $totalIncome = $transactionDetails->sum('subtotal');

        return view('kasirs.daily_report', [
            'transactionDetails' => $transactionDetails,
            'totalIncome' => $totalIncome,
        ], compact('user'));
    }


}



