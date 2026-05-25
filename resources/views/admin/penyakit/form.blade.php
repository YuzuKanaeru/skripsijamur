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
<div class="mb-3">
    <label class="form-label">Gambar Penyakit (opsional)</label>
    <input type="file" name="image" accept="image/*" class="form-control">
    @if(!empty($penyakit) && isset($penyakit->id))
        @php
            $imgPath = null;
            $exts = ['jpg','jpeg','png','webp','gif'];
            foreach ($exts as $ext) {
                if (file_exists(public_path('images/penyakit/'.$penyakit->id.'.'.$ext))) {
                    $imgPath = asset('images/penyakit/'.$penyakit->id.'.'.$ext);
                    break;
                }
            }
        @endphp
        @if($imgPath)
            <div style="margin-top:8px">
                <img src="{{ $imgPath }}" alt="gambar" style="max-width:240px;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,0.08)">
            </div>
        @endif
    @endif
</div>
<button class="btn btn-primary">Simpan</button>
