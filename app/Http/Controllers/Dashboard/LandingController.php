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
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'wedding_date' => 'nullable|date',
        ]);

        $landing = Landing::first() ?? new Landing();

        if ($request->hasFile('hero_image')) {
            if ($landing->hero_image && Storage::exists('public/' . $landing->hero_image)) {
                Storage::delete('public/' . $landing->hero_image);
            }
            $path = $request->file('hero_image')->store('landing', 'public');
            $validatedData['hero_image'] = $path;
        }

        $landing->fill($validatedData);
        $landing->save();

        return redirect()->back()->with('success', 'Data landing page berhasil diperbarui.');
    }
}
