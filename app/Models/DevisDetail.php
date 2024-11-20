<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_id',
        'article_id',
        'qte_cmde',
        'prix_unit',
        'unite_mesure_id',
    ];
}
