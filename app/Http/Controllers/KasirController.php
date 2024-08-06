<?php

// app/Http/Controllers/KasirController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionDetail; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewTransactionNotification;

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

    // // app/Http/Controllers/KasirController.php
    // public function saveTransaction(Request $request)
    // {
    //     $request->validate([
    //         'tenant_id' => 'required|exists:tenants,id',
    //         'items' => 'required|array',
    //         'items.*.menuId' => 'required|exists:menus,id',
    //         'items.*.tenantId' => 'required|exists:tenants,id',
    //         'items.*.menuPrice' => 'required|numeric',
    //         'items.*.menuName' => 'required|string',
    //         'items.*.tenantName' => 'required|string',
    //         'items.*.quantity' => 'required|integer|min:1',
    //         'items.*.notes' => 'nullable|string',
    //     ]);
    
    //     $tenant_id = $request->input('tenant_id');
    //     $items = $request->input('items');
        
    //     // Simpan transaksi dan detailnya
    //     $transaction = TransactionDetail::create([
    //         'tenant_id' => $tenant_id,
    //         'amount' => array_sum(array_map(function($item) {
    //             return $item['menuPrice'] * $item['quantity'];
    //         }, $items)),
    //         'date' => now(),
    //     ]);
    
    //     foreach ($items as $item) {
    //         $transaction->details()->create([
    //             'menu_id' => $item['menuId'],
    //             'tenant_id' => $item['tenantId'],
    //             'price' => $item['menuPrice'],
    //             'quantity' => $item['quantity'],
    //             'notes' => $item['notes'],
    //         ]);
    //     }
    
    //     return response()->json(['success' => true]);
    // }

    public function saveTransaction(Request $request)
    {
        $transactionDetail = TransactionDetail::create([
            'tenant_id' => $request->tenant_id,
            'amount' => $request->amount,
            'date' => now(),
            'status' => 'pending',
        ]);

        $tenant = Tenant::find($request->tenant_id);
        $tenant->notify(new NewTransactionNotification($transactionDetail));

        return response()->json(['success' => true]);
    }

    // app/Http/Controllers/OrderController.php
// app/Http/Controllers/TenantController.php

// app/Http/Controllers/TenantController.php

public function acceptOrder(Request $request, $orderId)
{
    try {
        $order = Order::findOrFail($orderId);
        $order->status = 'accepted';
        $order->save();

        // Simpan notifikasi di session untuk kasir
        $notifications = session('notifications', []);
        $notifications[] = [
            'message' => 'Pesanan ID ' . $orderId . ' telah diterima oleh tenant.',
            'status' => 'success'
        ];
        session(['notifications' => $notifications]);

        // Redirect kembali ke halaman order tenant
        return redirect()->route('tenant.orders')->with('success', 'Pesanan telah diterima.');
    } catch (\Exception $e) {
        return redirect()->route('tenant.orders')->with('error', 'Gagal menerima pesanan. Pesan kesalahan: ' . $e->getMessage());
    }
}

public function rejectOrder(Request $request, $orderId)
{
    try {
        $order = Order::findOrFail($orderId);
        $order->status = 'rejected';
        $order->save();

        // Simpan notifikasi di session untuk kasir
        $notifications = session('notifications', []);
        $notifications[] = [
            'message' => 'Pesanan ID ' . $orderId . ' telah ditolak oleh tenant.',
            'status' => 'error'
        ];
        session(['notifications' => $notifications]);

        // Redirect kembali ke halaman order tenant
        return redirect()->route('tenant.orders')->with('success', 'Pesanan telah ditolak.');
    } catch (\Exception $e) {
        return redirect()->route('tenant.orders')->with('error', 'Gagal menolak pesanan. Pesan kesalahan: ' . $e->getMessage());
    }
}


}



