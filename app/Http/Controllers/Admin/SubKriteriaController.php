<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use App\Models\Kriteria;

class SubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = SubKriteria::with('kriteria')->orderBy('id');
        // optional filter by kriteria id (from ?kriteria=)
        if (request()->has('kriteria')) {
            $kid = request()->query('kriteria');
            $query->where('kriteria_id', $kid);
        }
        $subs = $query->get();
        return view('admin.sub-kriteria.index', compact('subs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kriterias = Kriteria::orderBy('id')->get();
        return view('admin.sub-kriteria.create', compact('kriterias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'nama_sub' => 'required|string|max:100',
            'nilai' => 'required|integer',
        ]);
        SubKriteria::create($data);
        return redirect()->route('admin.sub-kriteria.index')->with('success', 'Sub Kriteria dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubKriteria $subKriteria)
    {
        return redirect()->route('admin.sub-kriteria.edit', $subKriteria);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubKriteria $subKriteria)
    {
        $kriterias = Kriteria::orderBy('id')->get();
        $sub = $subKriteria;
        return view('admin.sub-kriteria.edit', compact('sub', 'kriterias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKriteria $subKriteria)
    {
        $data = $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'nama_sub' => 'required|string|max:100',
            'nilai' => 'required|integer',
        ]);
        $subKriteria->update($data);
        return redirect()->route('admin.sub-kriteria.index')->with('success', 'Sub Kriteria diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubKriteria $subKriteria)
    {
        $subKriteria->delete();
        return redirect()->route('admin.sub-kriteria.index')->with('success', 'Sub Kriteria dihapus');
    }
}
