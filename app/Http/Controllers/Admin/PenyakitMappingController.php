<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penyakit;
use App\Models\Kriteria;
use App\Models\PenyakitSubKriteria;

class PenyakitMappingController extends Controller
{
    public function edit(Penyakit $penyakit)
    {
        $kriterias = Kriteria::with('subKriterias')->orderBy('id')->get();
        $mappings = PenyakitSubKriteria::where('penyakit_id', $penyakit->id)->get()->keyBy('kriteria_id');
        return view('admin.penyakit.mapping', compact('penyakit','kriterias','mappings'));
    }

    public function update(Request $request, Penyakit $penyakit)
    {
        $data = $request->validate([
            'selections' => 'required|array',
        ]);

        // delete existing mappings for this penyakit
        PenyakitSubKriteria::where('penyakit_id', $penyakit->id)->delete();

        foreach ($data['selections'] as $kriteriaId => $subId) {
            if ($subId) {
                PenyakitSubKriteria::create([
                    'penyakit_id' => $penyakit->id,
                    'kriteria_id' => $kriteriaId,
                    'sub_kriteria_id' => $subId,
                ]);
            }
        }

        return redirect()->route('admin.penyakit.edit', $penyakit)->with('success','Mapping diperbarui');
    }
}
