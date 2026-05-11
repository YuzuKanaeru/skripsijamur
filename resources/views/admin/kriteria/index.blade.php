@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="margin-bottom:12px;display:flex;align-items:center;justify-content:space-between">
        <h1 style="margin:0">Kriteria</h1>
        <a href="{{ route('admin.kriteria.create') }}" class="btn btn-sm btn-primary">Tambah Kriteria</a>
    </div>

    <div class="card">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Bobot</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kriterias as $k)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $k->kode_kriteria }}</td>
                <td>{{ $k->nama_kriteria }}</td>
                <td>{{ $k->bobot }}</td>
                <td>{{ $k->jenis }}</td>
                <td>
                    <div style="display:flex;gap:8px;align-items:center">
                        <a href="{{ route('admin.sub-kriteria.index') }}?kriteria={{ $k->id }}" class="btn btn-sm btn-outline-primary" title="Lihat atau tambahkan Sub Kriteria untuk kriteria ini">Sub Kriteria</a>
                        <a href="{{ route('admin.kriteria.edit', $k->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kriteria?')">Hapus</button>
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
