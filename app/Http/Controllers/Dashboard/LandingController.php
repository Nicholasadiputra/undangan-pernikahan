<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    public function index()
    {
        $landing = Landing::first() ?? new Landing();
        return view('dashboard.editlanding', compact('landing'));
    }

    public function update(Request $request)
    {
        $landing = Landing::first() ?? new Landing();

        // 1. Data Teks & Tanggal
        $landing->template        = $request->input('template', 'bohemian');

        // 2. Warna Custom (default sesuai template jika tidak diisi)
        $defaultColors = $landing->template === 'modern'
            ? ['#1E2A3A', '#8FA3B1', '#4A6572', '#F4F6F7']
            : ['#321E04', '#C9A96E', '#7A5C3A', '#f5f1eb'];

        $landing->color_primary = $request->input('color_primary', $defaultColors[0]);
        $landing->color_accent  = $request->input('color_accent',  $defaultColors[1]);
        $landing->color_mid     = $request->input('color_mid',     $defaultColors[2]);
        $landing->color_bg      = $request->input('color_bg',      $defaultColors[3]);

        $landing->groom_name      = $request->input('groom_name');
        $landing->bride_name      = $request->input('bride_name');
        $landing->wedding_date    = $request->input('wedding_date');
        $landing->lokasi_wedding  = $request->input('lokasi_wedding');
        $landing->kota            = $request->input('kota');
        $landing->map_iframe      = $request->input('map_iframe');

        // 2. Orang Tua
        $landing->ayah_pria    = $request->input('ayah_pria');
        $landing->ibu_pria     = $request->input('ibu_pria');
        $landing->ayah_wanita  = $request->input('ayah_wanita');
        $landing->ibu_wanita   = $request->input('ibu_wanita');

        // 3. Dress Code
        $landing->dresscode_text = $request->input('dresscode_text');

        // 4. Ucapan / Cerita
        $landing->cerita_bertemu = $request->input('cerita_bertemu');
        $landing->cerita_melamar = $request->input('cerita_melamar');

        // 5. Kegiatan / Acara (array → JSON)
        $names   = $request->input('kegiatan_name', []);
        $times   = $request->input('kegiatan_time', []);
        $periods = $request->input('kegiatan_period', []);

        $kegiatan = [];
        foreach ($names as $i => $name) {
            if (trim($name) === '') continue;
            if (count($kegiatan) >= 8) break;
            $kegiatan[] = [
                'name'   => $name,
                'time'   => $times[$i] ?? '',
                'period' => $periods[$i] ?? 'AM',
            ];
        }
        $landing->kegiatan = json_encode($kegiatan);

        // 6. Palette Dress Code (array → JSON)
        $palette = $request->input('palette_colors', []);
        $landing->palette_colors = json_encode(array_values($palette));

        // 7. Checkbox / Boolean
        $landing->show_animation  = $request->has('show_animation');
        $landing->play_music      = $request->has('play_music');
        $landing->show_guest_name = $request->has('show_guest_name');
        $landing->is_private      = $request->has('is_private');

        // 9. Galeri dari path yang sudah diupload
        $galleryPaths = $request->input('gallery_paths', []);
        $gallerySlots = $request->input('gallery_slots', []);

        $gallery = [];
        foreach ($galleryPaths as $i => $path) {
            if (!$path) continue;
            $gallery[] = [
                'path' => $path,
                'slot' => $gallerySlots[$i] ?? '',
                'url'  => Storage::url($path),
            ];
        }
        $landing->gallery = json_encode($gallery);

        $landing->save();

        return redirect()->back()->with('success', 'Pengaturan Landing Page berhasil disimpan.');
    }

    public function uploadGallery(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('landing/gallery', 'public');
            return response()->json(['path' => $path]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}