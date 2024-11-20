<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dataVente extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant_facture',
        'taux_remise',
        'montant_total',
        'montant_regle',
        'facture_type_id',
        'vente_id',
        'tva',
        'aib',
    ];
}
