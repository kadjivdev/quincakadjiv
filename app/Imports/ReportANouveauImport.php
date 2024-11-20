<?php

namespace App\Imports;

use App\Helpers\StringHelper;
use App\Models\AcompteClient;
use App\Models\Client;
use App\Models\FactureAncienne;
use App\Models\FactureType;
use App\Models\CompteClient;
use App\Models\Encaissement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReportANouveauImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        $nbr = count(FactureAncienne::all());
        $client = Client::where('nom_client', $row['nomclient'])->first();
        $type = FactureType::where('libelle', 'Simple')->first();
        // dd([substr($row['solde'], 0, 1) == '1', $client]);
        if (!is_null($client)) {
            if (substr($row['solde'], 0, 1) == '-') {
                $facture = FactureAncienne::create([
                    'date_facture' => now(),
                    'statut' => 'Non soldé',
                    'client_id' => $client->id,
                    'montant_facture' => substr($row['solde'], 1),
                    'montant_total' =>  substr($row['solde'], 1),
                    'num_facture' => 'FO' . date('dmY') . ($nbr + 1),
                    'user_id' => Auth::user()->id,
                    'facture_type_id' => $type->id,

                ]);
                $compte_client = CompteClient::create([
                    'date_op' => now(),
                    'montant_op' => substr($row['solde'], 1),
                    'client_id' =>$client->id,
                    'user_id'=> Auth::user()->id,
                    'type_op' => 'FAC_AC',
                ]);
            } else {
                $nbr = AcompteClient::max('id');
                $lettres = strtoupper(substr(StringHelper::removeAccents(Auth::user()->name), 0, 3));
                $code = 'KAD-'. 'ACC' . ($nbr + 1).'-'.date('dmY') . '-' . $lettres;

                $client->acompte_total = $client->acompte_total + (float)$row['solde'];
                $client->save();

                $facture = AcompteClient::create([
                    'montant_acompte' => $row['solde'],
                    'client_id' => $client->id,
                    'user_id' => Auth::id(),
                    'code' => $code,
                    'type_reglement' => 'Espèce',
                ]);

                if (Auth::user()->hasRole('CAISSE')) {
                    $encaissement = new Encaissement();
                    $encaissement->user_id = Auth::user()->id;
                    $facture->encaissements()->save($encaissement);
                }

                $compte_client = CompteClient::create([
                    'date_op' => now(),
                    'montant_op' => $row['solde'],
                    'client_id' =>$client->id,
                    'user_id'=> Auth::user()->id,
                    'type_op' => 'REG_AC',
                ]);
            }

        return $facture;

        }
    }
}
