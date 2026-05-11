@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Input Nilai - {{ $penyakit->nama_penyakit }}</h1>
    <form method="post" action="{{ route('admin.penyakit.nilai.update', $penyakit) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr><th>Kriteria</th><th>Bobot | Jenis</th><th>Nilai (angka)</th></tr>
                    </thead>
                    <tbody>
                        @foreach($kriterias as $k)
                        <tr>
                            <td>{{ $k->nama_kriteria }}</td>
                            <td>Bobot: {{ number_format($k->bobot,2) }} | Jenis: {{ $k->jenis }}</td>
                            <td>
                                <select name="nilai[{{ $k->id }}]" class="form-control">
                                    <option value="">-- pilih --</option>
                                    <option value="1" {{ (isset($values[$k->id]) && $values[$k->id] == 1) ? 'selected' : '' }}>1</option>
                                    <option value="3" {{ (isset($values[$k->id]) && $values[$k->id] == 3) ? 'selected' : '' }}>3</option>
                                    <option value="5" {{ (isset($values[$k->id]) && $values[$k->id] == 5) ? 'selected' : '' }}>5</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    <button class="btn btn-primary">Simpan Nilai</button>
                    <a href="{{ route('admin.penyakit.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
