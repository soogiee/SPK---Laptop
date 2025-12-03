<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Evaluation;

class LaptopRecommendation extends Component
{
    // Properti Form Input Kriteria
    public $newCriteriaName;
    public $newCriteriaType = 'benefit';
    public $newCriteriaWeight;

    // Properti Form Input Laptop
    public $newAlternativeName;

    // --- FUNGSI 1: Tambah & Hapus Kriteria ---
    public function addCriteria()
    {
        $this->validate([
            'newCriteriaName' => 'required',
            'newCriteriaWeight' => 'required|numeric',
        ]);

        Criteria::create([
            'name' => $this->newCriteriaName,
            'type' => $this->newCriteriaType,
            'weight' => $this->newCriteriaWeight,
        ]);

        $this->reset(['newCriteriaName', 'newCriteriaWeight']);
    }

    public function deleteCriteria($id)
    {
        Criteria::destroy($id);
    }

    // --- FUNGSI 2: Tambah & Hapus Laptop ---
    public function addAlternative()
    {
        $this->validate(['newAlternativeName' => 'required']);

        Alternative::create(['name' => $this->newAlternativeName]);
        $this->reset('newAlternativeName');
    }

    public function deleteAlternative($id)
    {
        Alternative::destroy($id);
    }

    // --- FUNGSI 3: Simpan Nilai Skor (Otomatis saat diketik) ---
    public function updateScore($alternative_id, $criteria_id, $value)
    {
        // Jika kosong, anggap 0
        $val = ($value === "" || $value === null) ? 0 : $value;

        Evaluation::updateOrCreate(
            [
                'alternative_id' => $alternative_id,
                'criteria_id' => $criteria_id
            ],
            ['value' => $val]
        );
    }

    // --- FUNGSI UTAMA: Render & Hitung TOPSIS ---
    public function render()
    {
        $alternatives = Alternative::with('evaluations')->get();
        $criterias = Criteria::all();
        $results = [];

        // Cek apakah data cukup untuk menghitung
        if ($alternatives->isNotEmpty() && $criterias->isNotEmpty()) {
            
            // 1. Hitung Pembagi (Divisor)
            $divisors = [];
            foreach ($criterias as $c) {
                // Ambil semua nilai untuk kriteria ini
                $values = Evaluation::where('criteria_id', $c->id)->pluck('value')->toArray();
                
                // Rumus: akar dari jumlah kuadrat
                $sumSquares = 0;
                foreach ($values as $val) {
                    $sumSquares += pow($val, 2);
                }
                $divisors[$c->id] = sqrt($sumSquares);
            }

            // 2. Buat Matriks Ternormalisasi Terbobot
            $weightedMatrix = [];
            
            foreach ($alternatives as $alt) {
                foreach ($criterias as $c) {
                    // Ambil nilai, jika tidak ada default 0
                    $eval = $alt->evaluations->where('criteria_id', $c->id)->first();
                    $val = $eval ? $eval->value : 0;
                    
                    // Normalisasi: nilai / divisor
                    $normalized = ($divisors[$c->id] > 0) ? ($val / $divisors[$c->id]) : 0;
                    
                    // Terbobot: normalized * bobot
                    $weightedMatrix[$alt->id][$c->id] = $normalized * $c->weight;
                }
            }

            // 3. Cari Solusi Ideal Positif (A+) dan Negatif (A-)
            $idealPos = [];
            $idealNeg = [];

            foreach ($criterias as $c) {
                // Ambil satu kolom nilai untuk kriteria ini dari matriks terbobot
                $colValues = [];
                foreach ($alternatives as $alt) {
                    $colValues[] = $weightedMatrix[$alt->id][$c->id];
                }

                if (empty($colValues)) $colValues = [0];

                if ($c->type === 'benefit') {
                    $idealPos[$c->id] = max($colValues);
                    $idealNeg[$c->id] = min($colValues);
                } else {
                    // Jika Cost: Makin kecil makin ideal (positif)
                    $idealPos[$c->id] = min($colValues);
                    $idealNeg[$c->id] = max($colValues);
                }
            }

            // 4. Hitung Jarak (D+ dan D-) & Nilai Preferensi (V)
            foreach ($alternatives as $alt) {
                $sumPos = 0;
                $sumNeg = 0;

                foreach ($criterias as $c) {
                    $val = $weightedMatrix[$alt->id][$c->id];
                    $sumPos += pow($val - $idealPos[$c->id], 2);
                    $sumNeg += pow($val - $idealNeg[$c->id], 2);
                }

                $dPos = sqrt($sumPos);
                $dNeg = sqrt($sumNeg);
                
                // Rumus V = D- / (D- + D+)
                $score = ($dPos + $dNeg) > 0 ? $dNeg / ($dNeg + $dPos) : 0;

                $results[] = [
                    'id' => $alt->id,
                    'name' => $alt->name,
                    'score' => $score,
                    'detail' => [
                        'd_plus' => number_format($dPos, 4),
                        'd_min' => number_format($dNeg, 4)
                    ]
                ];
            }

            // 5. Ranking (Urutkan dari skor terbesar)
            usort($results, fn($a, $b) => $b['score'] <=> $a['score']);
        }

        return view('livewire.laptop-recommendation', [
            'results' => $results,
            'alternatives' => $alternatives,
            'criterias' => $criterias
        ]);
    }
}