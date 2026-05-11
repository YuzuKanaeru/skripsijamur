@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="margin-bottom:12px;display:flex;align-items:center;justify-content:space-between">
        <h1 style="margin:0">Edit Penyakit</h1>
        <div style="display:flex;gap:8px;align-items:center">
            <a href="{{ route('admin.penyakit.nilai.edit', $penyakit->id) }}" class="btn btn-outline-success" title="Input nilai numerik untuk penyakit">Input Nilai</a>
            <a href="{{ route('admin.penyakit.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>
    <div class="card">
    <form action="{{ route('admin.penyakit.update', $penyakit->id) }}" method="POST">
        @method('PUT')
        @include('admin.penyakit.form')
    </form>
    </div>
</div>
@endsection
