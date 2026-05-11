@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mapping: {{ $penyakit->nama_penyakit }}</h1>
    <form action="{{ route('admin.penyakit.mapping.update', $penyakit) }}" method="POST">
        @csrf @method('PUT')
        @foreach($kriterias as $k)
            <div class="mb-3">
                <label>{{ $k->nama_kriteria }}</label>
                <select name="selections[{{ $k->id }}]" class="form-control">
                    <option value="">-- pilih sub kriteria --</option>
                    @foreach($k->subKriterias as $s)
                        <option value="{{ $s->id }}" {{ (isset($mappings[$k->id]) && $mappings[$k->id]->sub_kriteria_id == $s->id) ? 'selected' : '' }}>{{ $s->nama_sub }} ({{ $s->nilai }})</option>
                    @endforeach
                </select>
            </div>
        @endforeach
        <button class="btn btn-primary">Simpan Mapping</button>
    </form>
</div>
@endsection
