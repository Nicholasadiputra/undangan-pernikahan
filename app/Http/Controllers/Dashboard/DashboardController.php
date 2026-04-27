<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tamu;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Tamu::selectRaw("
            COUNT(*) AS total,
            SUM(status = 'Hadir') AS hadir,
            SUM(status = 'Tidak Hadir') AS tidak_hadir,
            SUM(status = 'Menunggu') AS menunggu
        ")->first();

        $total      = (int) ($stats->total       ?? 0);
        $hadir      = (int) ($stats->hadir       ?? 0);
        $tidakHadir = (int) ($stats->tidak_hadir ?? 0);
        $menunggu   = (int) ($stats->menunggu    ?? 0);

        return view('dashboard.index', compact('total', 'hadir', 'tidakHadir', 'menunggu'));
    }
}
