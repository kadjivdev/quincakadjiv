<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte_pv extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_op',
        'cle',
        'montant_op',
        'livraison_pv_id',
        'facture_id',
        'point_vente_id',
        'user_id',
        'type_op',
    ];
}
