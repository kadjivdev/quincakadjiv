<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneSupplementCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'qte_cmde',
        'article_id',
        'supplement_commande_id',
        'prix_unit',
        'unite_mesure_id',
    ];
}
