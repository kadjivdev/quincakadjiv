<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleFacture extends Model
{
    use HasFactory;

    protected $fillable = [
        'facture_id',
        'article_id',
        'qte_cmd',
        'prix_unit',
        'unite_mesure_id',
    ];
}
