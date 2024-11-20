<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticlePointVente extends Pivot
{
    use HasFactory;

    protected $table = 'article_point_ventes';

    protected $fillable = [
        'article_id',
        'point_vente_id',
        'qte_stock',
        'prix_special',
        'prix_revendeur',
        'prix_particulier',
        'prix_btp',
    ];

}
