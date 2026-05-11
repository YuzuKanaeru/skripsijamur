@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h1 style="margin:0">Daftar Penyakit</h1>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Penyakit</th>
                    <th>Deskripsi</th>
                    <th>Solusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($list as $p)
                <tr>
                    <td>{{ $loop->iteration + ($list->currentPage()-1)*$list->perPage() }}</td>
                    <td>{{ $p->nama_penyakit }}</td>
                    <td>{!! nl2br(e($p->deskripsi)) !!}</td>
                    <td>{!! nl2br(e($p->solusi)) !!}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Belum ada data penyakit.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px">{{ $list->links() }}</div>
    </div>
</div>
@endsection
