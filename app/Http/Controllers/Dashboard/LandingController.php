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

        // 8. Upload Thumbnail Custom
        if ($request->hasFile('custom_thumbnail')) {
            if ($landing->custom_thumbnail && Storage::exists('public/' . $landing->custom_thumbnail)) {
                Storage::delete('public/' . $landing->custom_thumbnail);
            }
            $landing->custom_thumbnail = $request->file('custom_thumbnail')
                ->store('landing/thumbnails', 'public');
        }

        // 10. Upload HTML Custom
        if ($request->hasFile('custom_html')) {
            if ($landing->custom_html && Storage::exists('public/' . $landing->custom_html)) {
                Storage::delete('public/' . $landing->custom_html);
            }
            $landing->custom_html = $request->file('custom_html')
                ->store('landing/html', 'public');
        }

        // 11. Galeri dari path yang sudah diupload
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