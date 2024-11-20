<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('departements')->insert([
            ['libelle' => 'Atacora', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Donga', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Borgou', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Alibori', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Zou', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Collines', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Mono', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Couffo', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Atlantique', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Littoral', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Oueme', 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'Plateau', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
