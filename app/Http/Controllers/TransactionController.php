<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Tenant;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data dari request
        $request->validate([
            'order_data' => 'required|json',
            'payment_method' => 'required|string',
            'amount_given' => 'required|numeric|min:0'
        ]);
    
        // Ambil data pesanan, metode pembayaran, dan jumlah uang yang diberikan
        $orderData = json_decode($request->input('order_data'), true);
        $paymentMethod = $request->input('payment_method');
        $amountGiven = $request->input('amount_given');
    
        if (empty($orderData) || !is_array($orderData)) {
            return redirect()->back()->with('error', 'Data pesanan tidak valid.');
        }
    
        // Mulai transaksi database
        DB::beginTransaction();
    
        try {
            // Ambil tenantId dari orderData
            $tenantId = $orderData[0]['tenantId'] ?? null;
    
            if (is_null($tenantId)) {
                throw new \Exception('ID tenant tidak ditemukan.');
            }
    
            // Hitung total amount dan kembalian
            $totalAmount = array_sum(array_map(fn($item) => $item['menuPrice'] * $item['quantity'], $orderData));
            $changeAmount = $amountGiven - $totalAmount;
    
            // Simpan transaksi
            $transaction = new Transaction();
            $transaction->tenant_id = $tenantId; // Menggunakan kolom tenant_id
            $transaction->amount = $totalAmount;
            $transaction->date = now();
            $transaction->save();
    
            // Simpan detail transaksi
            foreach ($orderData as $item) {
                $transactionDetail = new TransactionDetail();
                $transactionDetail->transaction_id = $transaction->id;
                $transactionDetail->tenant_id = $tenantId;
                $transactionDetail->tenant_name = $item['tenantName'] ?? 'N/A';
                $transactionDetail->menu_name = $item['menuName'] ?? 'N/A';
                $transactionDetail->quantity = $item['quantity'] ?? 0;
                $transactionDetail->price = $item['menuPrice'] ?? 0;
                $transactionDetail->subtotal = ($item['menuPrice'] ?? 0) * ($item['quantity'] ?? 0);
                $transactionDetail->notes = $item['notes'] ?? '';
                $transactionDetail->payment_method = $paymentMethod;
                $transactionDetail->amount_given = $amountGiven;
                $transactionDetail->change_amount = $changeAmount;
                $transactionDetail->save();
            }
    
            // Commit transaksi
            DB::commit();
    
            // Redirect dengan pesan sukses
            return redirect()->route('payment-detail')->with('success', 'Pesanan berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan. Pesan kesalahan: ' . $e->getMessage());
        }
    }
    
    public function saveTransaction(Request $request)
    {
        try {
            $items = $request->input('items', []);
            session(['temporary_transaction' => $items]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function paymentDetail()
    {
        $user = Auth::user();
        $items = session('temporary_transaction', []);
        $tenantId = session('tenant_id'); // Ambil tenant_id dari session jika ada
        return view('kasirs.payment-detail', compact('items', 'tenantId','user'));
    }

    public function penjualan(Request $request)
    {
        $user = Auth::user();
        $date = Carbon::today(); // Atau gunakan tanggal yang sesuai
        $filter = $request->input('filter', 'daily'); // Ambil filter dari request, default 'daily'

        // Logika untuk filter data
        switch ($filter) {
            case 'daily':
                $date = Carbon::today();
                $dateEnd = Carbon::tomorrow();
                break;
            case 'weekly':
                $date = Carbon::now()->startOfWeek();
                $dateEnd = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $date = Carbon::now()->startOfMonth();
                $dateEnd = Carbon::now()->endOfMonth();
                break;
            case 'yearly':
                $date = Carbon::now()->startOfYear();
                $dateEnd = Carbon::now()->endOfYear();
                break;
            default:
                $date = Carbon::today();
                $dateEnd = Carbon::tomorrow();
        }

    // Ambil data statistik penjualan
    $tenantStatistics = DB::table('transactions')
    ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
    ->join('tenant', 'transactions.tenant_id', '=', 'tenant.id') // Join dengan tabel tenants untuk mendapatkan nama tenant
    ->select('transactions.tenant_id', 'tenant.namatenant as tenant_namatenant', // Menggunakan nama tabel dan kolom yang benar
             DB::raw('SUM(transactions.amount) as total_income'), 
             DB::raw('SUM(transaction_details.quantity) as total_orders'))
    ->whereDate('transactions.created_at', $date)
    ->groupBy('transactions.tenant_id', 'tenant.namatenant') // Kelompokkan berdasarkan tenant_id dan nama tenant
    ->get();
        
        return view('penjualan', [
            'user' => $user,
            // 'tenantNames' => $transactionDetails,
            'tenantStatistics' => $tenantStatistics,
            'date' => $date->toDateString(), // Mengirimkan tanggal sebagai string
            'dateEnd' => $dateEnd->toDateString(),
            'filter' => $filter // Kirimkan filter ke view
        ]);
    }
    

    public function pembayaran()
    {
        $user = Auth::user();
        return view('pembayaran', compact('user'));
    }

    public function todaySalesReport()
    {
        $user = Auth::user();

        // Pastikan pengguna ada dan memiliki tenant_id
        if (!$user || !$user->tenant_id) {
            return view('dashboard', ['todaySales' => collect()]);
        }

        // Dapatkan ID tenant dari pengguna
        $tenantId = $user->tenant_id;

        // Ambil transaksi hari ini untuk tenant yang sesuai
        $todaySales = DB::table('transaction_details')
            ->join('tenants', 'transaction_details.tenant_id', '=', 'tenants.id')
            ->where('transaction_details.tenant_id', $tenantId)
            ->whereDate('transaction_details.date', today())
            ->select('tenants.name as tenant_name', 
                     DB::raw('count(*) as transaction_count'), 
                     DB::raw('sum(transaction_details.amount) as total_amount'))
            ->groupBy('tenants.name')
            ->get();


        return view('tenantHome', ['todaySales' => $todaySales]);
    }

}
