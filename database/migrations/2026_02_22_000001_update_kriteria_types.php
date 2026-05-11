<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update kriteria whose name contains 'kondisi' or 'bau' (case-insensitive)
        DB::table('kriterias')
            ->whereRaw("LOWER(nama_kriteria) LIKE '%kondisi%'")
            ->orWhereRaw("LOWER(nama_kriteria) LIKE '%bau%'")
            ->update(['jenis' => 'benefit']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert - set back to 'cost' for those same records
        DB::table('kriterias')
            ->whereRaw("LOWER(nama_kriteria) LIKE '%kondisi%'")
            ->orWhereRaw("LOWER(nama_kriteria) LIKE '%bau%'")
            ->update(['jenis' => 'cost']);
    }
};
