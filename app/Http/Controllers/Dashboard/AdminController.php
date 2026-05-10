<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Landing;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin')->get();
        $landing = Landing::first() ?? new Landing();
        return view('dashboard.dataAdmin', compact('users', 'landing'));
    }

    public function store(Request $request)
    {
        User::create([
            'name'     => $request->username, // Menyamakan name dengan username
            'email'    => $request->username . '@example.com', // Email dummy agar valid
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
        ]);

        $data = [
            'username' => $request->username,
            'role'     => 'admin',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // 1. Cegah hapus diri sendiri
        if (auth()->id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun yang sedang Anda gunakan.');
        }

        // 2. Cegah hapus admin terakhir
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus admin terakhir. Minimal harus ada satu akun admin di sistem.');
        }

        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }
}