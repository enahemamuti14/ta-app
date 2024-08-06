<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        // Ambil semua user beserta role-nya
        $users = User::with('roles')->get();

        // Kirim data ke view
        return view('userRole', ['users' => $users], compact('user'));
    }
    public function createForm()
    {
        $user = Auth::user();
        $roles = Role::all();
        $tenants = Tenant::all();

        return view('userCreate', compact('user','roles', 'tenants'));
    }

    // Metode untuk menghapus user
    public function destroy($id)
    {
        $user = Auth::user();
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

     // Metode untuk menampilkan form edit user
     public function editID($id)
     {
         $user = Auth::user();
         $user = User::findOrFail($id);
         $roles = Role::all(); // Ambil semua role untuk dropdown
         return view('userEdit', compact('user', 'roles'));
     }

    // Metode untuk memproses update user
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string',
            'password' => 'nullable|string|confirmed',
        ]);

        // Temukan user berdasarkan ID
        $user = User::findOrFail($id);

        // Update data user
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Simpan perubahan
        $user->save();

        // Redirect atau respon sesuai kebutuhan
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'required|string|exists:roles,id',
            'tenant_id' => 'nullable|integer|exists:tenant,id',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $request->tenant_id,
        ]);

        $role = Role::find($request->role);
    
        if ($role) {
            // Tambahkan role ke user
            $user->roles()->attach($role->id);
        } else {
            // Handle kasus jika role tidak ditemukan
            return redirect()->route('users.create')->withErrors('Role tidak ditemukan');
        }
    
        return redirect()->route('users.create')->with('success', 'User created successfully');
    }
    
}
