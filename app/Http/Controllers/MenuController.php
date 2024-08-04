<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->input('query');
        $menus = Menu::where('name', 'LIKE', "%{$query}%")->get();
        
        $tenantIds = $menus->pluck('tenant_id')->unique();
        $tenants = Tenant::whereIn('id', $tenantIds)->with(['menus' => function ($query) use ($menus) {
            $query->whereIn('id', $menus->pluck('id'));
        }])->get();

        return view('kasirs.search-results', compact('tenants', 'query', 'user'));
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $menus = Menu::with('tenant')->get();
        $tenants = Tenant::all(); // Ambil data tenant untuk filter
        // $menuTypes = MenuType::all(); // Ambil data jenis menu untuk filter jika ada

        return view('kasirs.menu', compact('menus', 'tenants', 'user'));
    }

}
