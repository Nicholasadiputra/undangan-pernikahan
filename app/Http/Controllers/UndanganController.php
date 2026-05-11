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

    public function show($slug)
    {
        $landing = Landing::first() ?? new Landing();
        $guest = Tamu::where('slug', $slug)->first();

        return view('undangan.index', compact('landing', 'guest'));
    }

    public function utama()
    {
        $landing = Landing::first() ?? new Landing();
        $guest = null;
        $guestSlug = session('guest_slug');

        if ($guestSlug) {
            $guest = Tamu::where('slug', $guestSlug)->first();
        }

        // Pilih view berdasarkan template yang disimpan di database
        $template = $landing->template ?? 'bohemian';
        $view = in_array($template, ['bohemian', 'modern'])
            ? 'undangan.' . $template
            : 'undangan.bohemian';

        return view($view, compact('landing', 'guest'));
    }

    public function rsvp(Request $request)
    {
        try {
            $request->validate([
                'kehadiran' => 'required|in:Hadir,Tidak Hadir',
                'kategori'  => 'required|in:Keluarga,Teman,Rekan',
                'pax'       => 'required|integer|min:0',
            ]);

            if ($request->filled('guest_id')) {
                $tamu = Tamu::find($request->input('guest_id'));
                if ($tamu) {
                    $tamu->update([
                        'kategori' => $request->kategori,
                        'pax'      => $request->pax,
                        'status'   => $request->kehadiran,
                        'ucapan'   => $request->pesan,
                    ]);

                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Terima kasih, konfirmasi Anda telah diperbarui.'
                    ]);
                }
            }

            $request->validate([
                'nama' => 'required|string|max:255',
            ]);

            Tamu::create([
                'nama'     => $request->nama,
                'kategori' => $request->kategori,
                'pax'      => $request->pax,
                'status'   => $request->kehadiran,
                'ucapan'   => $request->pesan,
                'slug'     => Tamu::makeUniqueSlug($request->nama),
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