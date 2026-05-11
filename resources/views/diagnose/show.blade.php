@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h1 style="margin:0">Hasil Diagnosa - {{ $dataJamur->tanggal }}</h1>
        <div>
            <a href="{{ route('diagnose.index') }}" class="btn btn-secondary">Kembali ke Diagnosa</a>
            @if (Route::has('diagnose.print'))
                <a href="{{ route('diagnose.print', $dataJamur) }}" target="_blank" class="btn btn-outline-primary" style="margin-left:8px">Cetak</a>
            @else
                <a href="{{ url('/diagnose/'.$dataJamur->id.'/print') }}" target="_blank" class="btn btn-outline-primary" style="margin-left:8px">Cetak</a>
            @endif
        </div>
    </div>

    <h3>Detail Input</h3>
    <ul>
        @foreach($dataJamur->detailDataJamur as $d)
            <li>{{ $d->kriteria->nama_kriteria ?? '' }}: {{ $d->subKriteria->nama_sub ?? '' }} ({{ $d->nilai }})</li>
        @endforeach
    </ul>

    <h3>Hasil SAW (Ranking)</h3>
    <table class="table">
        <thead><tr><th>#</th><th>Penyakit</th><th>Score</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($dataJamur->hasilSaws->sortBy('ranking') as $h)
            <tr>
                <td>{{ $h->ranking }}</td>
                <td>{{ $h->penyakit->nama_penyakit }}</td>
                <td>{{ number_format($h->nilai_preferensi,3) }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" onclick="toggleSolusi({{ $h->penyakit->id }})">Solusi</button>
                </td>
            </tr>
            <tr id="solusi-{{ $h->penyakit->id }}" class="solusi-row" style="display:none">
                <td colspan="4">
                    <div class="card" style="background:#fbfbff;padding:12px">
                        <h4 style="margin-top:0">Deskripsi</h4>
                        <div style="color:var(--muted)">{!! nl2br(e($h->penyakit->deskripsi)) !!}</div>
                        <h4 style="margin-top:12px">Solusi</h4>
                        <div>{!! nl2br(e($h->penyakit->solusi)) !!}</div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(isset($matrix))
    <h3>Matrix Perhitungan (Detail)</h3>

    <h4>Matriks Awal (x_ij)</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Penyakit</th>
                @foreach($matrix['kriterias'] as $k)
                    <th>{{ $k->nama_kriteria }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($matrix['penyakits'] as $p)
            <tr>
                <td>{{ $p->nama_penyakit }}</td>
                @foreach($matrix['kriterias'] as $k)
                    <td>{{ $matrix['x'][$p->id][$k->id] ?? 0 }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Normalisasi (rij)</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Penyakit</th>
                @foreach($matrix['kriterias'] as $k)
                    <th>{{ $k->nama_kriteria }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($matrix['penyakits'] as $p)
            <tr>
                <td>{{ $p->nama_penyakit }}</td>
                    @foreach($matrix['kriterias'] as $k)
                    <td>{{ number_format($matrix['normalized'][$p->id][$k->id] ?? 0,3) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Normalisasi × Bobot (w * rij)</h4>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Penyakit</th>
                @foreach($matrix['kriterias'] as $k)
                    <th>{{ $k->nama_kriteria }}<br/>(w={{ number_format($matrix['weights'][$k->id] ?? 0,2) }})</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrix['penyakits'] as $p)
            <tr>
                <td>{{ $p->nama_penyakit }}</td>
                @php $total = 0; @endphp
                    @foreach($matrix['kriterias'] as $k)
                    @php $val = $matrix['weighted'][$p->id][$k->id] ?? 0; $total += $val; @endphp
                    <td>{{ number_format($val,3) }}</td>
                @endforeach
                <td><strong>{{ number_format($total,3) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<script>
    function toggleSolusi(id){
        var el = document.getElementById('solusi-'+id);
        if(!el) return;
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'table-row' : 'none';
        if(el.style.display === 'table-row') el.scrollIntoView({behavior:'smooth', block:'center'});
    }
</script>
</div>
@endsection
