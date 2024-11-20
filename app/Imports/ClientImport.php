<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $nbr = Client::max('id');
        $code = 'CL'. formaterCode($nbr + 1);

        return new Client([
            'nom_client' => $row['nomclient'],
            'code_client' => $code,
            'email' => $row['email'],
            'phone' => $row['telclient'],
            'address' => $row['adresseclient'],
            'seuil' => $row['seuil'],
            'categorie' => 'VIP',

        ]);
    }
}
