@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <h1>{{ $penyakit->nama_penyakit }}</h1>
        @if($penyakit->deskripsi)
            <h4>Deskripsi</h4>
            <p>{{ $penyakit->deskripsi }}</p>
        @endif
        @if($penyakit->solusi)
            <h4>Solusi</h4>
            <div class="card" style="padding:12px">{!! nl2br(e($penyakit->solusi)) !!}</div>
        @else
            <p><em>Tidak ada solusi tersedia untuk penyakit ini.</em></p>
        @endif
        <div style="margin-top:12px">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
