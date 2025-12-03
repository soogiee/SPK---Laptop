<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Evaluation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kriteria
        // Cost: Harga (makin murah makin bagus)
        // Benefit: RAM & Storage (makin besar makin bagus)
        $c1 = Criteria::create(['name' => 'Harga (Juta)', 'type' => 'cost', 'weight' => 0.4]);
        $c2 = Criteria::create(['name' => 'RAM (GB)', 'type' => 'benefit', 'weight' => 0.3]);
        $c3 = Criteria::create(['name' => 'Storage (GB)', 'type' => 'benefit', 'weight' => 0.3]);

        // 2. Buat Alternatif (Laptop)
        $l1 = Alternative::create(['name' => 'Asus Vivobook']);
        $l2 = Alternative::create(['name' => 'MacBook Air']);
        $l3 = Alternative::create(['name' => 'Acer Aspire']);

        // 3. Masukkan Nilai (Matriks Keputusan)
        
        // Asus (Harga 8jt, RAM 8, Storage 512)
        Evaluation::create(['alternative_id' => $l1->id, 'criteria_id' => $c1->id, 'value' => 8]);
        Evaluation::create(['alternative_id' => $l1->id, 'criteria_id' => $c2->id, 'value' => 8]);
        Evaluation::create(['alternative_id' => $l1->id, 'criteria_id' => $c3->id, 'value' => 512]);

        // MacBook (Harga 18jt, RAM 16, Storage 256)
        Evaluation::create(['alternative_id' => $l2->id, 'criteria_id' => $c1->id, 'value' => 18]);
        Evaluation::create(['alternative_id' => $l2->id, 'criteria_id' => $c2->id, 'value' => 16]);
        Evaluation::create(['alternative_id' => $l2->id, 'criteria_id' => $c3->id, 'value' => 256]);
        
        // Acer (Harga 6jt, RAM 4, Storage 1000/1TB)
        Evaluation::create(['alternative_id' => $l3->id, 'criteria_id' => $c1->id, 'value' => 6]);
        Evaluation::create(['alternative_id' => $l3->id, 'criteria_id' => $c2->id, 'value' => 4]);
        Evaluation::create(['alternative_id' => $l3->id, 'criteria_id' => $c3->id, 'value' => 1000]);
    }
}