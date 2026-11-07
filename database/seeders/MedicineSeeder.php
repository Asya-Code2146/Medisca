<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            'Paracetamol', 'Tablet Tambah Darah', 'Entrostop', 'Promag', 
            'Mefenamic Acid', 'Cetirizine', 'Antasid', 'Diatabs', 
            'Ibuprofen', 'Ambroxol', 'Feminax', 'Geliga', 
            'HotIn Cream', 'Fresh Care', 'GPU', 'Minkay Kayu Putih', 
            'Minyak Tawon', 'Kasa', 'Bufacetine', 'Betadine', 
            'Kapas', 'Hansaplast'
        ];

        foreach ($medicines as $name) {
            Medicine::create([
                'name' => $name,
                'category' => 'Umum',
                'stock' => 10, 
                'description' => 'Obat standar UKS',
            ]);
        }
    }
}