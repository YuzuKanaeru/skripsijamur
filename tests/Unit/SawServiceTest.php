<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Penyakit;
use App\Models\PenyakitSubKriteria;
use App\Services\SawService;
use App\Models\DataJamur;

class SawServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_saw_ranks_alternatives_correctly()
    {
        // create two criteria (weights 2 and 1, both benefit)
        $k1 = Kriteria::create(['kode_kriteria' => 'K1', 'nama_kriteria' => 'Kriteria 1', 'bobot' => 2, 'jenis' => 'benefit']);
        $k2 = Kriteria::create(['kode_kriteria' => 'K2', 'nama_kriteria' => 'Kriteria 2', 'bobot' => 1, 'jenis' => 'benefit']);

        // sub-criteria values
        $s11 = SubKriteria::create(['kriteria_id' => $k1->id, 'nama_sub' => 'low', 'nilai' => 1]);
        $s12 = SubKriteria::create(['kriteria_id' => $k1->id, 'nama_sub' => 'high', 'nilai' => 2]);

        $s21 = SubKriteria::create(['kriteria_id' => $k2->id, 'nama_sub' => 'low', 'nilai' => 1]);
        $s22 = SubKriteria::create(['kriteria_id' => $k2->id, 'nama_sub' => 'high', 'nilai' => 3]);

        // two diseases A and B
        $a = Penyakit::create(['kode_penyakit' => 'A', 'nama_penyakit' => 'Alpha']);
        $b = Penyakit::create(['kode_penyakit' => 'B', 'nama_penyakit' => 'Beta']);

        // mappings
        // A: k1->high(2), k2->low(1)
        PenyakitSubKriteria::create(['penyakit_id' => $a->id, 'kriteria_id' => $k1->id, 'sub_kriteria_id' => $s12->id]);
        PenyakitSubKriteria::create(['penyakit_id' => $a->id, 'kriteria_id' => $k2->id, 'sub_kriteria_id' => $s21->id]);

        // B: k1->low(1), k2->high(3)
        PenyakitSubKriteria::create(['penyakit_id' => $b->id, 'kriteria_id' => $k1->id, 'sub_kriteria_id' => $s11->id]);
        PenyakitSubKriteria::create(['penyakit_id' => $b->id, 'kriteria_id' => $k2->id, 'sub_kriteria_id' => $s22->id]);

        $service = new SawService();
        $results = $service->compute(new DataJamur());

        $this->assertCount(2, $results);

        // A should score higher than B with these values
        $this->assertEquals('A', $results[0]['penyakit']->kode_penyakit);
        $this->assertEquals('B', $results[1]['penyakit']->kode_penyakit);

        // expected scores (approx)
        $this->assertEqualsWithDelta(0.7777778, $results[0]['score'], 0.0002);
        $this->assertEqualsWithDelta(0.6666666, $results[1]['score'], 0.0002);
    }
}
