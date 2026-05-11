<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyakits = Penyakit::orderBy('id')->get();
        return view('admin.penyakit.index', compact('penyakits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.penyakit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_penyakit' => 'required|string|max:10',
            'nama_penyakit' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'solusi' => 'nullable|string',
        ]);
        Penyakit::create($data);
        return redirect()->route('admin.penyakit.index')->with('success', 'Penyakit dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyakit $penyakit)
    {
        return redirect()->route('admin.penyakit.edit', $penyakit);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyakit $penyakit)
    {
        return view('admin.penyakit.edit', compact('penyakit'));
    }

    /**
     * Show form to input numeric nilai for a Penyakit (will be mapped to SubKriteria)
     */
    public function editNilai(Penyakit $penyakit)
    {
        $kriterias = \App\Models\Kriteria::with('subKriterias')->orderBy('id')->get();
        // load existing mapped numeric values if present (from PenyakitSubKriteria->subKriteria.nilai)
        $mappings = \App\Models\PenyakitSubKriteria::where('penyakit_id', $penyakit->id)->get()->keyBy('kriteria_id');
        $values = [];
        foreach ($mappings as $kId => $map) {
            $values[$kId] = $map->subKriteria?->nilai ?? null;
        }
        return view('admin.penyakit.nilai', compact('penyakit','kriterias','values'));
    }

    /**
     * Accept posted numeric nilai per kriteria for a Penyakit, map to nearest SubKriteria, and save mapping.
     */
    public function updateNilai(Request $request, Penyakit $penyakit)
    {
        $kriterias = \App\Models\Kriteria::orderBy('id')->get();
        $data = $request->validate([
            'nilai' => 'required|array'
        ]);

        // remove existing mappings for this penyakit (we'll recreate)
        \App\Models\PenyakitSubKriteria::where('penyakit_id', $penyakit->id)->delete();

        foreach ($data['nilai'] as $kId => $numVal) {
            if ($numVal === null || $numVal === '') continue;
            // find nearest subKriteria by numeric 'nilai' for this kriteria
            $subs = \App\Models\SubKriteria::where('kriteria_id', $kId)->get();
            $closest = null; $closestDiff = PHP_INT_MAX; $closestId = null;
            foreach ($subs as $s) {
                $d = abs($s->nilai - floatval($numVal));
                if ($d < $closestDiff) { $closestDiff = $d; $closest = $s; $closestId = $s->id; }
            }
            if ($closest) {
                \App\Models\PenyakitSubKriteria::create([
                    'penyakit_id' => $penyakit->id,
                    'kriteria_id' => $kId,
                    'sub_kriteria_id' => $closestId,
                ]);
            }
        }

        return redirect()->route('admin.penyakit.index')->with('success','Nilai penyakit disimpan dan dipetakan ke sub-kriteria');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyakit $penyakit)
    {
        $data = $request->validate([
            'kode_penyakit' => 'required|string|max:10',
            'nama_penyakit' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'solusi' => 'nullable|string',
        ]);
        $penyakit->update($data);
        return redirect()->route('admin.penyakit.index')->with('success', 'Penyakit diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyakit $penyakit)
    {
        // delete related mappings and hasil_saws first to satisfy foreign key constraints
        try {
            \DB::beginTransaction();
            // delete penyakit_sub_kriterias
            \App\Models\PenyakitSubKriteria::where('penyakit_id', $penyakit->id)->delete();
            // delete hasil_saws referencing this penyakit
            \App\Models\HasilSaw::where('penyakit_id', $penyakit->id)->delete();
            // now delete penyakit
            $penyakit->delete();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('admin.penyakit.index')->with('error', 'Gagal menghapus penyakit: '.$e->getMessage());
        }
        return redirect()->route('admin.penyakit.index')->with('success', 'Penyakit dihapus');
    }
}
