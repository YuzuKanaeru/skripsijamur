<?php

namespace App\Http\Controllers;

use App\Models\DataJamur;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Penyakit;
use App\Services\SawService;
use App\Models\DetailDataJamur;

class DataJamurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = DataJamur::with('user')->orderBy('created_at','desc');
        // non-admin users only see their own records
        if (!$user || ($user->role ?? '') !== 'admin') {
            $query->where('user_id', $user?->id ?? 0);
        }
        $list = $query->paginate(20);
        return view('diagnose.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kriterias = Kriteria::with('subKriterias')->orderBy('id')->get();
        return view('diagnose.create', compact('kriterias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'selections' => 'required|array',
        ]);
        $user = $request->user() ?: null;
        $dj = DataJamur::create(['user_id' => $user?->id ?? null, 'tanggal' => $data['tanggal']]);
        foreach ($data['selections'] as $kriteriaId => $subId) {
            DetailDataJamur::create([
                'data_jamur_id' => $dj->id,
                'kriteria_id' => $kriteriaId,
                'sub_kriteria_id' => $subId,
                'nilai' => SubKriteria::find($subId)->nilai ?? 0,
            ]);
        }

        // compute SAW
        $service = new SawService();
        $results = $service->compute($dj);
        // store hasil_saw
        $rank = 1;
        foreach ($results as $r) {
            $dj->hasilSaws()->create([
                'penyakit_id' => $r['penyakit']->id,
                'nilai_preferensi' => $r['score'],
                'ranking' => $rank++,
            ]);
        }
        return redirect()->route('diagnose.show', $dj);
    }

    /**
     * Display the specified resource.
     */
    public function show(DataJamur $dataJamur)
    {
        $dataJamur->load('detailDataJamur.kriteria','detailDataJamur.subKriteria','hasilSaws.penyakit');
        // compute detailed matrices to show on the view
        $service = new SawService();
        $matrix = $service->computeDetailed($dataJamur);
        return view('diagnose.show', compact('dataJamur','matrix'));
    }

    /**
     * Print-friendly view for a DataJamur (for browser PDF print)
     */
    public function print(DataJamur $dataJamur)
    {
        $dataJamur->load('detailDataJamur.kriteria','detailDataJamur.subKriteria','hasilSaws.penyakit');
        $service = new SawService();
        $matrix = $service->computeDetailed($dataJamur);
        return view('diagnose.print', compact('dataJamur','matrix'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataJamur $dataJamur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataJamur $dataJamur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataJamur $dataJamur)
    {
        $user = auth()->user();
        // only admin can delete records via UI
        if (!$user || ($user->role ?? '') !== 'admin') {
            abort(403);
        }
        try {
            $dataJamur->delete();
        } catch (\Exception $e) {
            return redirect()->route('diagnose.index')->with('error', 'Gagal menghapus diagnosa.');
        }
        return redirect()->route('diagnose.index')->with('success', 'Diagnosa berhasil dihapus.');
    }
}
