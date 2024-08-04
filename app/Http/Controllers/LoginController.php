<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    protected function redirectPath()
    {
        $user = Auth::user();
        if ($user->roles()->where('name', 'admin')->exists()) {
            return '/admin/dashboard';
        } elseif ($user->roles()->where('name', 'kasir')->exists()) {
            return '/kasir/dashboard';
        } elseif ($user->roles()->where('name', 'tenant')->exists()) {
            return '/tenant/dashboard';
        }

        return '/';
    }

    public function logout()
{
    Auth::logout();

    return redirect()->route('login'); // Redirect ke halaman login setelah logout
}
}
