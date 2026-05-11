<x-app-layout>
    <!-- page header removed to avoid duplicate 'Dashboard' title in sidebar area -->

    <div class="py-6">
        <div class="container">
            <div class="hero card">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <h1 style="margin:0;color:#0f172a">SIPJATI</h1>
                        <p style="margin:6px 0;color:var(--muted)">Sistem Pendukung Keputusan Untuk Menentukan Penyakit Pada Jamur Tiram</p>
                    </div>
                </div>
            </div>

            <div style="margin-top:18px" class="grid">
                <div class="card stat-card stat-blue">
                    <div>
                        <div style="font-size:14px">Jumlah Penyakit</div>
                        <div class="value">{{ $counts['penyakit'] ?? 0 }}</div>
                    </div>
                    <div style="font-size:28px;opacity:0.25">🦠</div>
                </div>

                <div class="card stat-card stat-pink">
                    <div>
                        <div style="font-size:14px">Jumlah Kriteria</div>
                        <div class="value">{{ $counts['kriteria'] ?? 0 }}</div>
                    </div>
                    <div style="font-size:28px;opacity:0.25">⚖️</div>
                </div>

                <div class="card stat-card stat-orange">
                    <div>
                        <div style="font-size:14px">Riwayat Penilaian</div>
                        <div class="value">{{ $counts['riwayat'] ?? 0 }}</div>
                    </div>
                    <div style="font-size:28px;opacity:0.25">📊</div>
                </div>
            </div>

            <div style="margin-top:18px">
                <div class="card">
                    <h3 style="margin-top:0">Ringkasan</h3>
                    <p style="color:var(--muted)">Tampilkan ringkasan data dan akses cepat untuk mengelola Kriteria, Penyakit, dan melihat hasil penilaian.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
