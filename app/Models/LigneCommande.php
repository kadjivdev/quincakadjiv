<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'qte_cmde',
        'quantity',
        'article_id',
        'commande_id',
        'prix_unit',
        'unite_mesure_id',
    ];


}
