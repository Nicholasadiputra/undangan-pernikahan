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

        // 1. Tangani Data Teks, Tanggal, & Iframe
        $landing->template = $request->input('template', 'bohemian');
        $landing->groom_name = $request->input('groom_name');
        $landing->bride_name = $request->input('bride_name');
        $landing->wedding_date = $request->input('wedding_date');
        $landing->lokasi_wedding = $request->input('lokasi_wedding');
        $landing->kota = $request->input('kota');
        $landing->map_iframe = $request->input('map_iframe'); 

        // 2. Tangani Data Checkbox (Boolean)
        $landing->show_animation = $request->has('show_animation');
        $landing->play_music = $request->has('play_music');
        $landing->show_guest_name = $request->has('show_guest_name');
        $landing->is_private = $request->has('is_private');


        // 3. Tangani Upload File Gambar (Hero Image)
        if ($request->hasFile('hero_image')) {
            if ($landing->hero_image && Storage::exists('public/' . $landing->hero_image)) {
                Storage::delete('public/' . $landing->hero_image);
            }
            $landing->hero_image = $request->file('hero_image')->store('landing/images', 'public');
        }

        // 4. Tangani Upload File HTML Custom
        if ($request->hasFile('custom_html')) {
            if ($landing->custom_html && Storage::exists('public/' . $landing->custom_html)) {
                Storage::delete('public/' . $landing->custom_html);
            }
            $landing->custom_html = $request->file('custom_html')->store('landing/html', 'public');
        }

        $landing->save();

        return redirect()->back()->with('success', 'Pengaturan Landing Page berhasil disimpan.');
    }
}
