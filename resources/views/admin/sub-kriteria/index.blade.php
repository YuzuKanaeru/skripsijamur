@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sub Kriteria</h1>
    <a href="{{ route('admin.sub-kriteria.create') }}" class="btn btn-sm btn-primary mb-3">Tambah Sub Kriteria</a>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Kriteria</th>
                <th>Nama Sub</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subs as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->kriteria->nama_kriteria ?? '' }}</td>
                <td>{{ $s->nama_sub }}</td>
                <td>{{ $s->nilai }}</td>
                <td>
                    <a href="{{ route('admin.sub-kriteria.edit', $s->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form action="{{ route('admin.sub-kriteria.destroy', $s->id) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
