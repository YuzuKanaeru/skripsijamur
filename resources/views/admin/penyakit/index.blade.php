@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h1 style="margin:0">Penyakit</h1>
        <a href="{{ route('admin.penyakit.create') }}" class="btn btn-sm btn-primary">Tambah Penyakit</a>
    </div>

    <div class="card">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penyakits as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->kode_penyakit }}</td>
                <td>{{ $p->nama_penyakit }}</td>
                <td>
                    <div class="actions" style="display:flex;gap:8px;align-items:center">
                        <a href="{{ route('admin.penyakit.nilai.edit', $p->id) }}" class="btn btn-sm btn-outline-success" title="Input nilai numerik untuk penyakit">Input Nilai</a>
                        <a href="{{ route('admin.penyakit.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.penyakit.destroy', $p->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
