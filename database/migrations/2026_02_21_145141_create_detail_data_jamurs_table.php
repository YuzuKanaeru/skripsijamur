<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_data_jamurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_jamur_id')->constrained('data_jamurs')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriterias');
            $table->foreignId('sub_kriteria_id')->constrained('sub_kriterias');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_data_jamurs');
    }
};
