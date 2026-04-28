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

    public function index(Request $request)
    {
        $query  = Tamu::orderBy('created_at', 'desc');
        $search = $request->query('search', '');

        if ($search !== '') {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
        }

        $total      = $query->count();
        $limit      = (int) $request->query('limit', 10);
        $page       = max(1, (int) $request->query('page', 1));
        $totalPages = $limit > 0 ? (int) ceil($total / $limit) : 1;
        $data       = $query->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json([
            'data'       => $data,
            'total'      => $total,
            'totalPages' => $totalPages,
        ]);
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