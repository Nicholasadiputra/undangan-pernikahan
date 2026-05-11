<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Imports\TamuImport;
use App\Models\Landing;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class TamuController extends Controller
{
    public function page()
    {
        // Mengambil data untuk dikirim ke view dashboard.dataTamu
        $tamus = Tamu::orderBy('created_at', 'desc')->get();
        $landing = Landing::first() ?? new Landing();
        return view('dashboard.dataTamu', compact('tamus', 'landing'));
    }

    public function index(Request $request)
    {
        $query  = Tamu::orderBy('created_at', 'desc');
        $search = $request->query('search', '');

        if ($search !== '') {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
        }

        $total      = $query->count();
        $limit      = (int) $request->query('limit', 10);
        $page       = max(1, (int) $request->query('page', 1));
        $totalPages = $limit > 0 ? (int) ceil($total / $limit) : 1;
        $data       = $query->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json([
            'data'       => $data,
            'total'      => $total,
            'totalPages' => $totalPages,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|in:Keluarga,Teman,Rekan',
            'pax'      => 'required|integer|min:0',
            'status'   => 'required|in:Hadir,Tidak Hadir,Menunggu',
            'ucapan'   => 'nullable|string'
        ]);

        $validated['slug'] = Tamu::makeUniqueSlug($validated['nama']);
        $tamu = Tamu::create($validated);
        return response()->json($tamu);
    }

    public function update(Request $request, $id)
    {
        $tamu = Tamu::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'kategori' => 'required|in:Keluarga,Teman,Rekan',
            'pax'      => 'required|integer|min:0',
            'status'   => 'required|in:Hadir,Tidak Hadir,Menunggu',
            'ucapan'   => 'nullable|string'
        ]);

        $validated['slug'] = Tamu::makeUniqueSlug($validated['nama'], $tamu->id);
        $tamu->update($validated);
        return response()->json($tamu);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            $sheets = Excel::toArray(new TamuImport, $request->file('file'));
            $rows = $sheets[0] ?? [];
            $success = 0;
            $skipped = 0;
            $failed = 0;

            if (!count($rows)) {
                return response()->json([
                    'message' => 'File Excel tidak berisi data.',
                    'success' => $success,
                    'skipped' => $skipped,
                    'failed'  => $failed,
                ]);
            }

            $headerRow = array_map(function ($value) {
                return strtolower(trim((string) $value));
            }, $rows[0]);

            $hasHeader = in_array('nama', $headerRow) || in_array('nama tamu', $headerRow);
            if ($hasHeader) {
                $rows = array_slice($rows, 1);
            }

            $headerIndexes = [
                'nama'     => null,
                'kategori' => null,
                'pax'      => null,
                'status'   => null,
                'ucapan'   => null,
            ];

            if ($hasHeader) {
                foreach ($headerRow as $index => $column) {
                    if (str_contains($column, 'nama')) {
                        $headerIndexes['nama'] = $index;
                    }
                    if (str_contains($column, 'kategori')) {
                        $headerIndexes['kategori'] = $index;
                    }
                    if (str_contains($column, 'pax')) {
                        $headerIndexes['pax'] = $index;
                    }
                    if (str_contains($column, 'status')) {
                        $headerIndexes['status'] = $index;
                    }
                    if (str_contains($column, 'ucapan') || str_contains($column, 'pesan') || str_contains($column, 'message')) {
                        $headerIndexes['ucapan'] = $index;
                    }
                }
            }

            foreach ($rows as $row) {
                $row = array_values((array) $row);

                $nama = '';
                if ($hasHeader && $headerIndexes['nama'] !== null) {
                    $nama = trim((string) ($row[$headerIndexes['nama']] ?? ''));
                } else {
                    $nama = trim((string) ($row[0] ?? ''));
                }

                if ($nama === '') {
                    $failed++;
                    continue;
                }

                $kategori = 'Teman';
                if ($hasHeader && $headerIndexes['kategori'] !== null) {
                    $kategoriValue = trim((string) ($row[$headerIndexes['kategori']] ?? ''));
                    if (in_array(strtolower($kategoriValue), ['keluarga', 'family'])) {
                        $kategori = 'Keluarga';
                    } elseif ($kategoriValue !== '') {
                        $kategori = $kategoriValue;
                    }
                }

                $status = 'Menunggu';
                if ($hasHeader && $headerIndexes['status'] !== null) {
                    $statusValue = trim((string) ($row[$headerIndexes['status']] ?? ''));
                    if (in_array(strtolower($statusValue), ['hadir', 'tidak hadir', 'menunggu'])) {
                        $status = ucfirst(strtolower($statusValue));
                    }
                }

                $pax = 0;
                if ($hasHeader && $headerIndexes['pax'] !== null) {
                    $paxValue = $row[$headerIndexes['pax']] ?? 0;
                    $pax = is_numeric($paxValue) ? (int) $paxValue : 0;
                }

                $ucapan = '';
                if ($hasHeader && $headerIndexes['ucapan'] !== null) {
                    $ucapan = trim((string) ($row[$headerIndexes['ucapan']] ?? ''));
                }

                $baseSlug = Tamu::slugify($nama);
                $existingTamu = Tamu::where('slug', $baseSlug)->first();

                if ($existingTamu) {
                    $existingTamu->update([
                        'nama'     => $nama,
                        'kategori' => $kategori,
                        'pax'      => $pax,
                        'status'   => $status,
                        'ucapan'   => $ucapan,
                    ]);
                    $skipped++;
                    continue;
                }

                Tamu::create([
                    'nama'     => $nama,
                    'kategori' => $kategori,
                    'pax'      => $pax,
                    'status'   => $status,
                    'ucapan'   => $ucapan,
                    'slug'     => Tamu::makeUniqueSlug($nama),
                ]);
                $success++;
            }

            return response()->json([
                'message' => 'Import selesai.',
                'success' => $success,
                'skipped' => $skipped,
                'failed'  => $failed,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengimpor file. Pastikan file valid dan format .xls atau .xlsx.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        Tamu::destroy($id);
        return response()->json(['success' => true]);
    }
}