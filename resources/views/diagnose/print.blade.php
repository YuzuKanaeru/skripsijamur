<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Diagnosa - {{ $dataJamur->tanggal }}</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        body{background:#fff;color:#111;padding:20px;font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial}
        .print-container{max-width:900px;margin:0 auto}
        .print-header{text-align:center;margin-bottom:18px}
        table{width:100%;border-collapse:collapse;margin-bottom:12px}
        table th, table td{border:1px solid #222;padding:8px}
        .no-border td{border:none}
    </style>
</head>
<body onload="window.print();" onafterprint="window.close();">
<div class="print-container">
    <div class="print-header">
        <h1>Hasil Diagnosa - {{ $dataJamur->tanggal }}</h1>
    </div>

    <h3>Detail Input</h3>
    <table class="no-border">
        <tbody>
        @foreach($dataJamur->detailDataJamur as $d)
            <tr class="no-border"><td style="width:220px">{{ $d->kriteria->nama_kriteria ?? '' }}</td><td>{{ $d->subKriteria->nama_sub ?? '' }} ({{ $d->nilai }})</td></tr>
        @endforeach
        </tbody>
    </table>

    <h3>Hasil SAW (Ranking)</h3>
    <table>
        <thead>
        <tr><th>#</th><th>Penyakit</th><th>Score</th></tr>
        </thead>
        <tbody>
        @foreach($dataJamur->hasilSaws->sortBy('ranking') as $h)
            <tr>
                <td>{{ $h->ranking }}</td>
                <td>{{ $h->penyakit->nama_penyakit }}</td>
                <td>{{ number_format($h->nilai_preferensi,3) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @if(isset($matrix))
    <h3>Matriks Awal (x_ij)</h3>
    <table>
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

    <h3>Normalisasi (rij)</h3>
    <table>
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
    @endif

    <p style="margin-top:18px;font-size:12px;color:#666">Dicetak pada: {{ now() }}</p>
</div>
</body>
</html>