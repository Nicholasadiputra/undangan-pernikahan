<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
