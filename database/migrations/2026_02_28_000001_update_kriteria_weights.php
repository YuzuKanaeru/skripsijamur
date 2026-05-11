<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Recommended weights (sum = 1.0)
        // Warna -> 0.30
        // Bau -> 0.25
        // Tekstur -> 0.20
        // Bekas Luka (kondisi) -> 0.15
        // Perubahan Ukuran -> 0.10

        DB::table('kriterias')->whereRaw("LOWER(nama_kriteria) LIKE '%warna%'")->update(['bobot' => 0.30]);
        DB::table('kriterias')->whereRaw("LOWER(nama_kriteria) LIKE '%bau%'")->update(['bobot' => 0.25]);
        DB::table('kriterias')->whereRaw("LOWER(nama_kriteria) LIKE '%tekstur%'")->update(['bobot' => 0.20]);
        DB::table('kriterias')->whereRaw("LOWER(nama_kriteria) LIKE '%bekas%'")->orWhereRaw("LOWER(nama_kriteria) LIKE '%kondisi%'")->update(['bobot' => 0.15]);
        DB::table('kriterias')->whereRaw("LOWER(nama_kriteria) LIKE '%perubahan ukuran%'")->update(['bobot' => 0.10]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to equal weights (fallback)
        $all = DB::table('kriterias')->count();
        $equal = $all ? round(1 / $all, 4) : 0.2;
        DB::table('kriterias')->update(['bobot' => $equal]);
    }
};
