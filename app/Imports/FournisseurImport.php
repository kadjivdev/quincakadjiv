<?php

namespace App\Imports;

use App\Models\Fournisseur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class FournisseurImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $nbr = Fournisseur::max('id');
        $code = 'FR' . formaterCode($nbr + 1);

        return new Fournisseur([
            'name' => $row['nom'],
            'code_frs' => $code,
            'email' => $row['email'],
            'phone' => $row['telephone'],
            'address' => $row['adresse'],
        ]);
    }

    public function rules(): array
    {
        return [
            // 'name' => ['required', 'string'],
        ];
    }

}
