<?php

namespace Database\Seeders;

use App\Models\FactureType;
use App\Models\TypeVente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactureTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FactureType::create([
            'libelle' => 'Normalisée',
        ]);

        FactureType::create([
            'libelle' => 'Simple',
        ]);


        // enregistrer les types de vente

        TypeVente::create([
            'libelle' => 'Simple',
        ]);
        TypeVente::create([
            'libelle' => 'BTP',
        ]);
        TypeVente::create([
            'libelle' => 'Revendeur',
        ]);
        TypeVente::create([
            'libelle' => 'Particulier',
        ]);
    }
}
