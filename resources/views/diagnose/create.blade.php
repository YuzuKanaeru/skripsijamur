@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Diagnosa Jamur</h1>
    <form action="{{ route('diagnose.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        @foreach($kriterias as $k)
            <div class="mb-3">
                <label>{{ $k->nama_kriteria }}</label>
                <select name="selections[{{ $k->id }}]" class="form-control" required>
                    <option value="">-- pilih --</option>
                    @foreach($k->subKriterias as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_sub }} ({{ $s->nilai }})</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <button class="btn btn-primary">Diagnosa</button>
    </form>
</div>
@endsection
