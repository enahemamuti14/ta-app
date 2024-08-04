<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        return view('tenantHome', compact('user'));
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
    // public function linkTenantToUser(Request $request, $userId)
    // {
    //     $request->validate([
    //         'tenant_id' => 'required|exists:tenants,id',
    //     ]);

    //     $user = User::findOrFail($userId);
    //     $user->tenant_id = $request->tenant_id;
    //     $user->save();

    //     return redirect()->back()->with('success', 'Tenant berhasil dihubungkan dengan pengguna.');
    // }
    // public function showLinkForm()
    // {
    //     $user = Auth::user();
    //     $tenants = Tenant::all();
    //     return view('tenants.link', compact('user', 'tenants'));
    // }
    // public function showUserTenant()
    // {
    //     $user = Auth::user();
    //     $tenant = $user->tenant; // Mengambil tenant terkait dengan pengguna yang sedang login

    //     return view('tenants.tenantUser', compact('tenant', 'user'));
    // }
}
