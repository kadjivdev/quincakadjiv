<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\CompteClient;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class ClientRanImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {

        $data = [];
        $userId = Auth::user()->id;
        $dateOp = Carbon::now();

        foreach ($rows as $row) {
            $montant = doubleval($row['montant']);
            $data[] = [
                'montant_op' => $montant < 0 ? -1 * $montant : $montant,
                'type_op' => $montant < 0 ? 'FAC-RAN' : 'REG-RAN',
                'client_id' => $row['client'],
                'user_id' => $userId,
                'date_op' => $dateOp,
                'created_at' => $dateOp,
                'updated_at' => $dateOp
            ];

            // Insérer par lots de 1000 pour éviter les dépassements de mémoire
            if (count($data) >= 1000) {
                $this->insertData($data);
                $data = [];
            }
        }

        // Insérer les lignes restantes
        if (!empty($data)) {
            $this->insertData($data);
        }
    }

    /**
    * Insère les données dans la base de données.
    *
    * @param array $data
    */
    protected function insertData(array $data)
    {
        DB::table('compte_clients')->insert($data);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
