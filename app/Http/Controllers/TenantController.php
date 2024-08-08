<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TenantController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenants = Tenant::all(); // Perbaiki nama variabel dari $tenant menjadi $tenants
        return view('tenants.index', compact('tenants','user'));
    }

    public function home()
    {
        $user = Auth::user();
        $tenantId = Auth::user()->tenant_id;
        $startDate = Carbon::now()->startOfWeek(); // Mulai dari awal minggu
        $endDate = Carbon::now()->endOfWeek(); // Akhir minggu ini
        $date = Carbon::today();
    
        // Ambil data pemasukan per hari selama seminggu
        $incomeData = Transaction::whereBetween('date', [$startDate, $endDate])
            ->where('tenant_id', $tenantId)
            ->selectRaw('DATE(date) as date, SUM(amount) as total_income')
            ->groupBy(DB::raw('DATE(date)')) // Mengelompokkan berdasarkan tanggal
            ->get();
    
        // Ambil data pesanan per hari selama seminggu
        $orderData = DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(DB::raw('DATE(transactions.created_at) as date, COUNT(transaction_details.id) as total_orders'))
            ->where('transactions.tenant_id', $tenantId)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(transactions.created_at)')) // Mengelompokkan berdasarkan tanggal
            ->get();
    
        // Ambil grafik pemasukan per menu selama seminggu
        $menuData = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->select(
                'transaction_details.menu_name as menu_name',
                DB::raw('SUM(transaction_details.quantity * transaction_details.price) as total_sales')
            )
            ->where('transactions.tenant_id', $tenantId)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy('transaction_details.menu_name')
            ->get();
    
  
        // Ambil tabel transaksi hari ini
        $todaySales = DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(
                'transactions.id as transaction_id',
                'transaction_details.menu_name as menu_name',
                'transaction_details.quantity',
                DB::raw('transaction_details.quantity * transaction_details.price as subtotal'),
                'transactions.created_at as date'
            )
            ->where('transactions.tenant_id', $tenantId)
            ->whereDate('transactions.created_at', $date)
            ->get();
    
        // Debugging data
        // dd([
        //     'Income Data' => $incomeData,
        //     'Order Data' => $orderData,
        //     'Menu Data' => $menuData,
        //     'Weekly Sales' => $todaySales
        // ]);
    
        return view('tenantHome', compact('user', 'incomeData', 'orderData', 'menuData', 'todaySales'));
    }
    
    
    

    public function create()
    {
        $user = Auth::user();
        return view('tenants.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggalmulaisewa' => 'required|date',
            'tanggalberakhirsewa' => 'required|date',
            'namatenant' => 'required|string|max:255',
            'statussewa' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
        ]);

        Tenant::create($request->all());

        return redirect()->route('tenants.index')->with('success', 'Tenant berhasil ditambahkan!');
    }

    public function show(Tenant $tenant)
    {
        $user = Auth::user();
        // Memuat relasi menus dan transactions
        $tenant->load('menus', 'transactions');
        
        return view('tenants.detailTenant', compact('tenant','user'));
    }
    public function transactions(Tenant $tenant)
    {
        $transactions = $tenant->transactions()->get();
        $user = Auth::user();
        return view('tenants.detailTenant',[
            'tenant' => $tenant,
            'transactions' => $transactions, // Pastikan relasi transaksi dimuat
            'user'=> $user,
        ]);
    }
    public function edit(Tenant $tenant)
    {
        $user = Auth::user();
        return view('tenants.edit', compact('tenant', 'user'));
    }

    public function update(Request $request, Tenant $tenant)
    {

        $request->validate([
            'tanggalmulaisewa' => 'required|date',
            'tanggalberakhirsewa' => 'required|date',
            'namatenant' => 'required|string|max:255',
            'statussewa' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
        ]);

        $tenant->update($request->all());

        return redirect()->route('tenants.index')
                         ->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('tenants.index')
                         ->with('success', 'Tenant deleted successfully.');
    }
    // Menambahkan metode untuk menampilkan formulir tambah menu
    public function createMenu($tenantId)
    {
        $user = Auth::user();
        $tenant = Tenant::findOrFail($tenantId);
        return view('tenants.create_menu', compact('tenant', 'user'));
    }

    public function storeMenu(Request $request, $tenantId)
    {
        $user = Auth::user();
        Log::info('Authenticated user:', ['user' => $user]);
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            // 'category' => 'required|string|in:Makanan,Minuman', // Validasi kategori
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi gambar
        ]);

        $tenant = Tenant::findOrFail($tenantId);

        // // Upload gambar jika ada
        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $imagePath = $image->store('menu_images', 'public'); // Menyimpan gambar di folder 'menu_images' dalam storage
        // }

        $tenant->menus()->create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            // 'category' => $request->category,
            // 'image' => $imagePath, // Simpan nama file gambar
        ]);

        return redirect()->route('tenants.show', $tenantId)
                        ->with('success', 'Menu berhasil ditambahkan!')
                        ->with('user', $user);
    }

      // Menampilkan form untuk mengedit menu
      public function editMenu($tenantId, $menuId)
      {
          $user = Auth::user();
          $tenant = Tenant::findOrFail($tenantId);
          $menu = Menu::findOrFail($menuId);
          return view('tenants.edit_menu', compact('tenant', 'menu','user'));
      }
  
      // Mengupdate menu
      public function updateMenu(Request $request, $tenantId, $menuId)
      {
          $request->validate([
              'name' => 'required|string|max:255',
              'price' => 'required|numeric',
              'description' => 'nullable|string',
          ]);
  
          $tenant = Tenant::findOrFail($tenantId);
          $menu = Menu::findOrFail($menuId);
  
          $menu->update([
              'name' => $request->name,
              'price' => $request->price,
              'description' => $request->description,
          ]);
  
          return redirect()->route('tenants.show', $tenantId)
                           ->with('success', 'Menu berhasil diupdate!');
      }
  
      // Menghapus menu
      public function destroyMenu($tenantId, $menuId)
      {
          $tenant = Tenant::findOrFail($tenantId);
          $menu = Menu::findOrFail($menuId);
  
          $menu->delete();
  
          return redirect()->route('tenants.show', $tenantId)
                           ->with('success', 'Menu berhasil dihapus!');
      }
      public function showAllMenus($tenantId)
    {
        $user = Auth::user();
        $tenant = Tenant::findOrFail($tenantId);
        $menus = $tenant->menus; // Asumsikan Anda memiliki relasi yang benar

        return view('tenants.menus_all', compact('tenant', 'menus', 'user'));
    }

    public function indexTenant()
    {
        $user = Auth::user();
        $tenants = Tenant::all();
        return view('kasirs.tenant', compact('tenants', 'user'));
    }

    public function showTenantMenu($id)
    {
        $user = Auth::user();
        $tenant = Tenant::findOrFail($id);
        $menus = $tenant->menus; // Asumsi ada relasi 'menus' pada model Tenant
        return view('kasirs.tenant_menu', compact('tenant', 'menus', 'user'));
    }
    
    public function salesDetails($tenant_id)
    {
        $user = Auth::user();
        $tenant = Tenant::findOrFail($tenant_id);

        $salesDetails = DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('tenant', 'transactions.tenant_id', '=', 'tenant.id')
            ->select('transactions.id as transaction_id', 'tenant.namatenant as tenant_name', 'transaction_details.menu_name as menu_name',
                    'transaction_details.quantity', 
                    DB::raw('transaction_details.quantity * transaction_details.price as subtotal'),
                    'transactions.created_at as date')
            ->where('transactions.tenant_id', $tenant_id)
            ->get();

        return view('detail-penjualan', compact('tenant', 'salesDetails','user'));
    }

    public function salestenant($tenant_id)
    {
        // Ambil tenant_id dari pengguna yang sedang login
        $tenantId = Auth::user()->tenant_id;
        // Ambil tenant berdasarkan ID yang diberikan
        $tenant = Tenant::findOrFail($tenant_id);

        // Ambil penjualan hari ini berdasarkan tenant_id
        $todaySales = DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('tenants', 'transactions.tenant_id', '=', 'tenants.id') // Sesuaikan nama tabel dengan 'tenants'
            ->select(
                'transactions.id as transaction_id',
                'tenants.namatenant as tenant_name',
                'transaction_details.menu_name as menu_name',
                'transaction_details.quantity',
                DB::raw('transaction_details.quantity * transaction_details.price as subtotal'),
                'transactions.created_at as date'
            )
            ->where('transactions.tenant_id', $tenant_id)
            ->whereDate('transactions.created_at', Carbon::today()) // Tambahkan filter untuk hari ini
            ->get();

        // Kirim data ke view
        return view('tenantHome', compact('todaySales', 'tenant'));
    }
    public function showlaporan(Request $request)
{
    $user = Auth::user();
    $tenantId = Auth::user()->tenant_id;
    $date = $request->input('date');
    $month = $request->input('month');
    $year = $request->input('year');

    $query = DB::table('transactions')
        ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->select(
            'transactions.id as transaction_id',
            'transaction_details.menu_name as menu_name',
            'transaction_details.quantity',
            DB::raw('transaction_details.quantity * transaction_details.price as subtotal'),
            'transactions.created_at as date'
        )
        ->where('transactions.tenant_id', $tenantId); // Filter berdasarkan tenant_id

    if ($date) {
        $query->whereDate('transactions.created_at', $date);
    } elseif ($month) {
        $query->whereYear('transactions.created_at', date('Y', strtotime($month)))
            ->whereMonth('transactions.created_at', date('m', strtotime($month)));
    } elseif ($year) {
        $query->whereYear('transactions.created_at', $year);
    } else {
        $query->whereDate('transactions.created_at', Carbon::today());
    }

    $salesDetails = $query->get();

    return view('tenants.laporanpenjualan', compact('salesDetails', 'user'));
}

    public function showmenu(Request $request)
    {
        $user = Auth::user(); // Ambil pengguna saat ini
        $tenantId = Auth::user()->tenant_id; // Ambil tenant_id pengguna saat ini

        $tenant = Tenant::findOrFail($tenantId);

        // Ambil query pencarian jika ada
        $query = $request->input('query');

        // Filter menu berdasarkan tenant_id dan query pencarian
        $menus = Menu::where('tenant_id', $tenantId)
            ->where(function ($queryBuilder) use ($query) {
                if ($query) {
                    $queryBuilder->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                }
            })
            ->get();

        return view('tenants.menus', compact('tenant', 'menus', 'user'));
    }

    // Menampilkan form tambah menu
    public function showupdate($id)
    {
        $user = Auth::user();
        $menu = Menu::findOrFail($id);
        
        return view('tenants.updatemenu', compact('menu','user'));
    }
    public function updatemenutenant(Request $request, $id)
    {
        // Ambil pengguna dan tenant saat ini
        $user = Auth::user(); 
        $tenantId = $user->tenant_id; 

        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Temukan menu berdasarkan ID
        $menu = Menu::findOrFail($id);

        // Opsional: Pastikan menu terkait dengan tenant yang benar
        if ($menu->tenant_id !== $tenantId) {
            return redirect()->route('tenants.menus')->with('error', 'Akses ditolak. Menu tidak terkait dengan tenant Anda.');
        }

        // Perbarui data menu
        $menu->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('showmenu')->with('success', 'Menu berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('showmenu')->with('success', 'Menu berhasil dihapus.');
    }

    // Menampilkan form tambah menu
    public function createmenutenant()
    {
        $user = Auth::user();
        return view('tenants.createMenu', compact('user'));
    }

    public function storemenutenant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $tenantId = Auth::user()->tenant_id;

        Menu::create([
            'tenant_id' => $tenantId,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        return redirect()->route('showmenu')->with('success', 'Menu berhasil ditambahkan.');
    }

    // // app/Http/Controllers/TenantController.php
    // public function showNotifications($id)
    // {
    //     $user = Auth::user();
    //     $tenant = Tenant::find($id);
    //     $transactions = TransactionDetail::where('tenant_id', $id)
    //                                 ->where('status', 'pending')
    //                                 ->get();
        
    //     return view('tenants.notifications', compact('tenant', 'transactions', 'user'));
    // }

    // public function approveTransaction($id, $transactionId)
    // {
    //     $transaction = TransactionDetail::find($transactionId);
    //     $transaction->status = 'approved';
    //     $transaction->save();

    //     return redirect()->route('tenant.notifications', ['id' => $id])
    //                     ->with('success', 'Pesanan disetujui');
    // }

    public function shownotif(TransactionDetail $transaction_detail)
    {
        $user = Auth::user();
        // $notifications = Auth::user()->notifications;
        
        return view('tenants.notifications', compact('transaction_detail', 'user'));
    }

    public function approve(TransactionDetail $transaction_detail)
    {
        $transaction_detail->status = 'approved';
        $transaction_detail->save();

        return redirect()->route('tenants.notifications', $transaction_detail)->with('success', 'Pesanan telah disetujui.');
    }
public function showOrders()
{
    $user = Auth::user();
    $items = Order::where('tenant_id', $user->tenant_id) // Ambil data sesuai tenant_id
                                ->orderBy('created_at', 'desc') // Atur urutan jika perlu
                                ->get(); 

    return view('tenants.notifications', compact('items', 'user'));
}


}
