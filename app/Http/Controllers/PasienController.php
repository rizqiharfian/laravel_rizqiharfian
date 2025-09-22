<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RumahSakit;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::with('rumahSakit')->get();
        $rs = RumahSakit::all();
        return view('pasien.index', compact('pasiens','rs'));
    }

    public function store(Request $request)
    {
        Pasien::create($request->all());
        return redirect()->back()->with('success','Pasien berhasil ditambah');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_telpon' => 'required|string|max:20',
            'rumah_sakit_id' => 'required|exists:rumah_sakits,id',
        ]);

        $ps = Pasien::findOrFail($id);
        $ps->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $ps
            ]);
        }

        return redirect()->back()->with('success','Data berhasil diupdate');
    }

    public function destroyAjax($id)
    {
        Pasien::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }

    public function filterByRumahSakit($rumahSakitId)
    {
        $pasiens = Pasien::with('rumahSakit')
            ->where('rumah_sakit_id', $rumahSakitId)
            ->get();

        $result = $pasiens->map(function($p){
            return [
                'id' => $p->id,
                'nama_pasien' => $p->nama_pasien,
                'alamat' => $p->alamat,
                'no_telpon' => $p->no_telpon,
                'rumah_sakit' => $p->rumahSakit
                    ? ['id' => $p->rumahSakit->id, 'nama_rumah_sakit' => $p->rumahSakit->nama_rumah_sakit]
                    : null
            ];
        });

        return response()->json($result);
    }
}

