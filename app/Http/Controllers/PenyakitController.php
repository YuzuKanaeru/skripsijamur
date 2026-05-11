<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    /**
     * Display a listing of diseases.
     */
    public function index()
    {
        $list = Penyakit::orderBy('nama_penyakit')->paginate(20);
        return view('penyakit.index', compact('list'));
    }

    /**
     * Display the specified disease (public view) including solusi.
     */
    public function show(Penyakit $penyakit)
    {
        return view('penyakit.show', compact('penyakit'));
    }
}
