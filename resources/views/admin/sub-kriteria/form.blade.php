@csrf
<div class="mb-3">
    <label class="form-label">Kriteria</label>
    <select name="kriteria_id" class="form-control" required>
        @foreach($kriterias as $kr)
            <option value="{{ $kr->id }}" {{ (old('kriteria_id', $sub?->kriteria_id ?? '') == $kr->id) ? 'selected' : '' }}>{{ $kr->nama_kriteria }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Nama Sub</label>
    <input type="text" name="nama_sub" class="form-control" value="{{ old('nama_sub', $sub->nama_sub ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Nilai</label>
    <input type="number" name="nilai" class="form-control" value="{{ old('nilai', $sub->nilai ?? '') }}" required>
</div>
<button class="btn btn-primary">Simpan</button>
