<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function page()
    {
        // Mengambil data untuk dikirim ke view dashboard.dataTamu
        $tamus = Tamu::orderBy('created_at', 'desc')->get();
        return view('dashboard.dataTamu', compact('tamus'));
    }

    public function index()
    {
        return response()->json(Tamu::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|in:Keluarga,Teman,Rekan',
            'pax'      => 'required|integer|min:0',
            'status'   => 'required|in:Hadir,Tidak Hadir,Menunggu',
            'ucapan'   => 'nullable|string'
        ]);

        $tamu = Tamu::create($validated);
        return response()->json($tamu);
    }

    public function update(Request $request, $id)
    {
        $tamu = Tamu::findOrFail($id);
        $tamu->update($request->all());
        return response()->json($tamu);
    }

    public function destroy($id)
    {
        Tamu::destroy($id);
        return response()->json(['success' => true]);
    }
}