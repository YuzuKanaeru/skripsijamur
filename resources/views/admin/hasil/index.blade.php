@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h1 style="margin:0">Hasil Penilaian (SAW)</h1>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Data Jamur</th>
                    <th>Penyakit</th>
                    <th>Nilai Preferensi</th>
                    <th>Ranking</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $r)
                <tr>
                    <td>{{ $loop->iteration + ($results->currentPage()-1)*$results->perPage() }}</td>
                    <td>{{ $r->dataJamur->tanggal ?? '—' }}</td>
                    <td>{{ $r->penyakit->nama_penyakit ?? '—' }}</td>
                    <td>{{ number_format($r->nilai_preferensi,3) }}</td>
                    <td>{{ $r->ranking }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:12px">{{ $results->links() }}</div>
    </div>
</div>
@endsection
