@csrf
<div class="mb-3">
    <label class="form-label">Kode Kriteria</label>
    <input type="text" name="kode_kriteria" class="form-control" value="{{ old('kode_kriteria', $kriteria->kode_kriteria ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Nama Kriteria</label>
    <input type="text" name="nama_kriteria" class="form-control" value="{{ old('nama_kriteria', $kriteria->nama_kriteria ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Bobot</label>
    <input type="number" step="0.01" name="bobot" class="form-control" value="{{ old('bobot', $kriteria->bobot ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Jenis</label>
    <select name="jenis" class="form-control">
        <option value="benefit" {{ (old('jenis', $kriteria->jenis ?? '') == 'benefit') ? 'selected' : '' }}>Benefit</option>
        <option value="cost" {{ (old('jenis', $kriteria->jenis ?? '') == 'cost') ? 'selected' : '' }}>Cost</option>
    </select>
</div>
<button class="btn btn-primary">Simpan</button>
