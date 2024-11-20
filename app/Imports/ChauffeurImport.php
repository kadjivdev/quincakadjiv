<?php

namespace App\Imports;

use App\Models\Chauffeur;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChauffeurImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Chauffeur([
            'nom_chauf' => $row['nomchauffeur'],
            'tel_chauf' => $row['telchauffeur'],
            'permis' => $row['permis'],
        ]);
    }
}
