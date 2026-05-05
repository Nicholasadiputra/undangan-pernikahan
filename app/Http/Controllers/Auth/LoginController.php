<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Tamu langsung masuk tanpa password
    public function tamuMasuk()
    {
        session(['login' => true, 'role' => 'tamu']);
        return redirect()->route('undangan.utama');
    }

    // Tampilkan form login admin
    public function showAdminLogin()
    {
        // Kalau sudah login sebagai admin, langsung ke dashboard
        if (session('role') === 'admin') {
            return redirect()->route('dashboard.index');
        }
        return view('dashboard.login');
    }

    // Proses login admin
    public function adminLogin(Request $request)
    {
        $user = User::where('username', $request->username)
                    ->where('role', 'admin')
                    ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'login'    => true,
                'user_id'  => $user->id,
                'username' => $user->username,
                'role'     => 'admin'
            ]);
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors(['login' => 'Username atau password salah.']);
    }

    // Logout semua role
    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}