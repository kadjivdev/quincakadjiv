<?php

namespace Database\Seeders;

use App\Models\Magasin;
use App\Models\PointVente;
use App\Models\UniteMesure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniteMesureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UniteMesure::create([
            'unite' => 'Barres',
            'abbrev' => 'bar',
        ]);

        UniteMesure::create([
            'unite' => 'Carton',
            'abbrev' => 'carton',
        ]);

        UniteMesure::create([
            'unite' => 'Feuilles',
            'abbrev' => 'feuille',
        ]);

        UniteMesure::create([
            'unite' => 'Mettre carré',
            'abbrev' => 'm2',
        ]);

        UniteMesure::create([
            'unite' => 'Paquet',
            'abbrev' => 'pqt',
        ]);

        UniteMesure::create([
            'unite' => 'Tonne',
            'abbrev' => 'T',
        ]);

        UniteMesure::create([
            'unite' => 'Kilogrammes',
            'abbrev' => 'Kg',
        ]);

        UniteMesure::create([
            'unite' => 'Pièce',
            'abbrev' => 'Kg',
        ]);

        UniteMesure::create([
            'unite' => 'Unité',
            'abbrev' => 'unité',
        ]);

        PointVente::create([
            'nom' => 'Boutique cotonou',
            'phone' => '229 96587411',
            'adresse' => 'Cotonou',
        ]);

        PointVente::create([
            'nom' => 'Boutique Parakou',
            'phone' => '229 96587621',
            'adresse' => 'Parakou',
        ]);

        PointVente::create([
            'nom' => 'Boutique Bohicon',
            'phone' => '229 96587652',
            'adresse' => 'Bohicon',
        ]);

        Magasin::create([
            'nom' => 'Magasin Parakou',
            'point_vente_id' => 2,
            'adresse' => 'Parakou',
        ]);

        Magasin::create([
            'nom' => 'Magasin Bohicon',
            'point_vente_id' => 3,
            'adresse' => 'Parakou',
        ]);

        Magasin::create([
            'nom' => 'Magasin 1 Cotonou',
            'point_vente_id' => 1,
            'adresse' => 'Cotonou',
        ]);

        Magasin::create([
            'nom' => 'Magasin 2 Cotonou',
            'point_vente_id' => 1,
            'adresse' => 'Cotonou',
        ]);

    }
}
