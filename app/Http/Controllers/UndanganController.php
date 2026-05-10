<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu;
use App\Models\Landing;

class UndanganController extends Controller
{
    public function index()
    {
        $landing = Landing::first() ?? new Landing();
        return view('undangan.index', compact('landing'));
    }

    public function utama()
    {
        $landing = Landing::first() ?? new Landing();

        // Pilih view berdasarkan template yang disimpan di database
        $template = $landing->template ?? 'bohemian';
        $view = in_array($template, ['bohemian', 'modern'])
            ? 'undangan.' . $template
            : 'undangan.bohemian';

        return view($view, compact('landing'));
    }

    public function rsvp(Request $request)
    {
        try {
            // Validasi data masuk
            $request->validate([
                'nama'      => 'required|string|max:255',
                'kehadiran' => 'required|in:Hadir,Tidak Hadir',
                'kategori'  => 'required|in:Keluarga,Teman,Rekan',
                'pax'       => 'required|integer|min:0',
            ]);

            // Simpan ke database sesuai struktur tabel tamu
            Tamu::create([
                'nama'     => $request->nama,
                'kategori' => $request->kategori,
                'pax'      => $request->pax,
                'status'   => $request->kehadiran,
                'ucapan'   => $request->pesan,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Terima kasih, konfirmasi Anda telah tersimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memproses data rsvp.'
            ], 500);
        }
    }
}