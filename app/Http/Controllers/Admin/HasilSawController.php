<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HasilSaw;

class HasilSawController extends Controller
{
    public function index()
    {
        $results = HasilSaw::with('penyakit','dataJamur')->orderBy('ranking')->paginate(30);
        return view('admin.hasil.index', compact('results'));
    }
}
