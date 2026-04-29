<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tamu;

class DashboardController extends Controller
{
    public function index()
    {
        $total      = Tamu::count();
        $hadir      = Tamu::where('status', 'Hadir')->count();
        $tidakHadir = Tamu::where('status', 'Tidak Hadir')->count();
        $menunggu   = Tamu::where('status', 'Menunggu')->count();

        return view('dashboard.index', compact('total', 'hadir', 'tidakHadir', 'menunggu'));
    }
}