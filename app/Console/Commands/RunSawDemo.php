<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataJamur;
use App\Models\DetailDataJamur;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Services\SawService;

class RunSawDemo extends Command
{
    protected $signature = 'runsaw:demo';
    protected $description = 'Create sample DataJamur, run SAW, and print ranking results';

    public function handle()
    {
        $this->info('Preparing demo diagnosis...');

        $kriterias = Kriteria::with('subKriterias')->get();

        // choose top sub_kriteria (highest nilai) for each kriteria to simulate input
        $selections = [];
        foreach ($kriterias as $k) {
            $best = $k->subKriterias->sortByDesc('nilai')->first();
            if ($best) $selections[$k->id] = $best->id;
        }

        $user = \App\Models\User::where('email','admin@example.com')->first();
        $dj = DataJamur::create(['user_id' => $user->id ?? 1, 'tanggal' => date('Y-m-d')]);
        foreach ($selections as $kId => $subId) {
            $sub = SubKriteria::find($subId);
            DetailDataJamur::create([
                'data_jamur_id' => $dj->id,
                'kriteria_id' => $kId,
                'sub_kriteria_id' => $subId,
                'nilai' => $sub?->nilai ?? 0,
            ]);
        }

        $this->info('Running SAW...');
        $service = new SawService();
        $results = $service->compute($dj);

        $this->info('Results:');
        $i = 1;
        foreach ($results as $r) {
            $this->line(sprintf("%d. %s — score: %0.4f", $i++, $r['penyakit']->nama_penyakit, $r['score']));
        }

        return 0;
    }
}
