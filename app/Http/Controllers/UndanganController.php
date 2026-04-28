<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tamu;

class UndanganController extends Controller
{
    public function index()
    {
        return view('undangan.index');
    }

    public function utama()
    {
        if (!session('login')) return redirect('/');

        return view('undangan.utama', [
            'mempelai_pria'    => 'Nicholas',
            'mempelai_wanita'  => 'Nahda',
            'tanggal_acara'    => 'SATURDAY - 2026 - DECEMBER',
            'lokasi_wedding'   => "Grand Ballroom, Ciputra's Hotel",
            'kota'             => 'Jakarta, Indonesia',
        ]);
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