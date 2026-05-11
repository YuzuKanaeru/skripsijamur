@csrf
<div class="mb-3">
    <label class="form-label">Kode Penyakit</label>
    <input type="text" name="kode_penyakit" class="form-control" value="{{ old('kode_penyakit', $penyakit->kode_penyakit ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Nama Penyakit</label>
    <input type="text" name="nama_penyakit" class="form-control" value="{{ old('nama_penyakit', $penyakit->nama_penyakit ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $penyakit->deskripsi ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Solusi</label>
    <textarea name="solusi" class="form-control">{{ old('solusi', $penyakit->solusi ?? '') }}</textarea>
</div>
<button class="btn btn-primary">Simpan</button>
