<?php

namespace App\Http\Controllers;

use App\Models\RumahSakit;
use Illuminate\Http\Request;

class RumahSakitController extends Controller
{
    public function index()
    {
        $data = RumahSakit::all();
        return view('rumahsakit.index', compact('data'));
    }

    public function store(Request $request)
    {
        RumahSakit::create($request->all());
        return redirect()->back()->with('success','Data berhasil ditambah');
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama_rumah_sakit' => 'required|string|max:255',
        'alamat' => 'required|string|max:500',
        'email' => 'required|email|max:255',
        'telepon' => 'required|string|max:20',
    ]);

    $rs = RumahSakit::findOrFail($id);
    $rs->update($validated);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $rs
        ]);
    }

    return redirect()->back()->with('success','Data berhasil diupdate');
}

    public function destroyAjax($id)
    {
        RumahSakit::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}

