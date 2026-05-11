<?php

namespace App\Services;

use App\Models\DataJamur;
use App\Models\Kriteria;
use App\Models\Penyakit;
use App\Models\SubKriteria;

class SawService
{
    /**
     * Compute SAW for a given DataJamur (by model or id).
     * Returns array of ['penyakit' => Penyakit, 'score' => float]
     */
    public function compute(DataJamur $dataJamur)
    {
        // SAW with user-input similarity: if user provided input for a kriteria,
        // use rij = 1 - abs(input - xij) / maxScale, otherwise fallback to classical SAW normalization.
        $maxScale = 5.0; // maximum scale for sub_kriteria (used for similarity)
        $kriterias = Kriteria::orderBy('id')->get();
        $weights = $kriterias->pluck('bobot','id')->toArray();
        $sumW = array_sum($weights);
        foreach ($weights as $k => $w) $weights[$k] = $sumW ? $w / $sumW : 0;

        $penyakits = Penyakit::all();

        // build decision matrix x
        $x = [];
        foreach ($penyakits as $p) {
            foreach ($kriterias as $k) {
                $mapping = $p->subKriteria()->where('kriteria_id', $k->id)->first();
                $val = 0;
                if ($mapping) $val = $mapping->subKriteria?->nilai ?? 0;
                $x[$p->id][$k->id] = $val;
            }
        }

        // compute max and min per criterion (column-wise)
        $max = [];
        $min = [];
        foreach ($kriterias as $k) {
            $col = [];
            foreach ($penyakits as $p) {
                $col[] = isset($x[$p->id][$k->id]) ? $x[$p->id][$k->id] : 0;
            }
            $max[$k->id] = count($col) ? max($col) : 0;
            $min[$k->id] = count($col) ? min($col) : 0;
        }

        // load user inputs for this DataJamur
        $dataJamur->load('detailDataJamur');
        $userInputs = [];
        foreach ($dataJamur->detailDataJamur as $d) {
            $userInputs[$d->kriteria_id] = $d->nilai;
        }

        // compute rij (use similarity if user input exists) and preference values
        $results = [];
        foreach ($penyakits as $p) {
            $score = 0.0;
            foreach ($kriterias as $k) {
                $xij = isset($x[$p->id][$k->id]) ? floatval($x[$p->id][$k->id]) : 0.0;
                if (isset($userInputs[$k->id])) {
                    $inputVal = floatval($userInputs[$k->id]);
                    $diff = abs($inputVal - $xij);
                    $rij = 1 - ($diff / $maxScale);
                    if ($rij < 0) $rij = 0;
                } else {
                    // fallback to classical normalization
                    if ($k->jenis === 'benefit') {
                        $rij = ($max[$k->id] > 0) ? ($xij / $max[$k->id]) : 0;
                    } else {
                        $rij = ($xij > 0) ? ($min[$k->id] / $xij) : 0;
                    }
                }
                $w = $weights[$k->id] ?? 0;
                $score += $w * $rij;
            }
            $results[] = ['penyakit' => $p, 'score' => $score];
        }

        usort($results, function($a,$b){ return $b['score'] <=> $a['score']; });
        return $results;
    }

    /**
     * Compute detailed matrices for a DataJamur: raw x, normalized rij, weighted w*rij, and final results.
     * Returns array with keys: kriterias, penyakits, weights, x, normalized, weighted, results
     */
    public function computeDetailed(DataJamur $dataJamur)
    {
        // Classical SAW detailed matrices
        $kriterias = Kriteria::orderBy('id')->get();
        $weights = $kriterias->pluck('bobot','id')->toArray();
        $sumW = array_sum($weights);
        foreach ($weights as $k => $w) $weights[$k] = $sumW ? $w / $sumW : 0;

        $penyakits = Penyakit::all();

        // decision matrix
        $x = [];
        foreach ($penyakits as $p) {
            foreach ($kriterias as $k) {
                $mapping = $p->subKriteria()->where('kriteria_id', $k->id)->first();
                $val = 0;
                if ($mapping) $val = $mapping->subKriteria?->nilai ?? 0;
                $x[$p->id][$k->id] = $val;
            }
        }

        // compute max/min per criterion (column-wise)
        $max = [];
        $min = [];
        foreach ($kriterias as $k) {
            $col = [];
            foreach ($penyakits as $p) {
                $col[] = isset($x[$p->id][$k->id]) ? $x[$p->id][$k->id] : 0;
            }
            $max[$k->id] = count($col) ? max($col) : 0;
            $min[$k->id] = count($col) ? min($col) : 0;
        }

        $normalized = [];
        $weighted = [];
        $weighted_similarity = [];
        $results = [];

        // load user inputs for this DataJamur
        $dataJamur->load('detailDataJamur');
        $userInputs = [];
        foreach ($dataJamur->detailDataJamur as $d) {
            $userInputs[$d->kriteria_id] = $d->nilai;
        }

        // For display: always compute classical normalization (rij = xij/max for benefit, min/xij for cost)
        // For ranking: if user provided inputs, compute similarity-based rij (1 - |input - xij|/maxScale) and use that to compute final scores.
        $maxScale = 5.0;
        foreach ($penyakits as $p) {
            $total_similarity = 0.0;
            foreach ($kriterias as $k) {
                $xij = isset($x[$p->id][$k->id]) ? floatval($x[$p->id][$k->id]) : 0.0;

                // classical rij for display
                if ($k->jenis === 'benefit') {
                    $rij_classic = $max[$k->id] > 0 ? ($xij / $max[$k->id]) : 0;
                } else {
                    $rij_classic = $xij > 0 ? ($min[$k->id] / $xij) : 0;
                }
                $normalized[$p->id][$k->id] = $rij_classic;

                $w = $weights[$k->id] ?? 0;
                $weighted[$p->id][$k->id] = $rij_classic * $w;

                // similarity-based rij for scoring (if input exists), otherwise fallback to classical
                if (isset($userInputs[$k->id])) {
                    $inputVal = floatval($userInputs[$k->id]);
                    $diff = abs($inputVal - $xij);
                    $rij_sim = 1 - ($diff / $maxScale);
                    if ($rij_sim < 0) $rij_sim = 0;
                } else {
                    $rij_sim = $rij_classic;
                }
                $weighted_similarity[$p->id][$k->id] = $rij_sim * $w;
                $total_similarity += $weighted_similarity[$p->id][$k->id];
            }
            $results[] = ['penyakit' => $p, 'score' => $total_similarity];
        }

        usort($results, function($a,$b){ return $b['score'] <=> $a['score']; });

        return [
            'kriterias' => $kriterias,
            'penyakits' => $penyakits,
            'weights' => $weights,
            'x' => $x,
            'max' => $max,
            'min' => $min,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'results' => $results,
        ];
    }
}
