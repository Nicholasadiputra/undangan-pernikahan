<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
// GET - list tamu dengan pagination
    public function index(Request $request)
    {
        $query = Tamu::query();
        if ($request->search) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        }
        $tamu = $query->orderBy('id', 'desc')->paginate($request->limit ?? 10);
        $stats = Tamu::selectRaw('COUNT(*) as total, 
            SUM(status="Hadir") as hadir,
            SUM(status="Tidak Hadir") as tidak_hadir,
            SUM(status="Menunggu") as menunggu')->first();

        return response()->json(['data' => $tamu->items(), 'total' => $tamu->total(), 'stats' => $stats]);
    }

    // POST
    public function store(Request $request)
    {
        $tamu = Tamu::create($request->all());
        return response()->json(['success' => true, 'id' => $tamu->id], 201);
    }

    // PUT
    public function update(Request $request, $id)
    {
        Tamu::findOrFail($id)->update($request->all());
        return response()->json(['success' => true]);
    }

    // DELETE
    public function destroy($id)
    {
        Tamu::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
