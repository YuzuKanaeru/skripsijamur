<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriterias = Kriteria::orderBy('id')->get();
        return view('admin.kriteria.index', compact('kriterias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_kriteria' => 'required|string|max:10',
            'nama_kriteria' => 'required|string|max:100',
            'bobot' => 'required|numeric',
            'jenis' => 'required|in:benefit,cost',
        ]);
        Kriteria::create($data);
        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kriteria $kriterium)
    {
        return redirect()->route('admin.kriteria.edit', ['kriterium' => $kriterium->id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriterium)
    {
        $kriteria = $kriterium;
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriterium)
    {
        $data = $request->validate([
            'kode_kriteria' => 'required|string|max:10',
            'nama_kriteria' => 'required|string|max:100',
            'bobot' => 'required|numeric',
            'jenis' => 'required|in:benefit,cost',
        ]);
        $kriterium->update($data);
        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriterium)
    {
        $kriterium->delete();
        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria dihapus');
    }
}
