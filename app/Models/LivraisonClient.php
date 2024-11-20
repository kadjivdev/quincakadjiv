<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivraisonClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'qte_livre',
        'article_id',
        'prix_unit',
        'unite_mesure_id',
        'bon_livraison_id',
        'magasin_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_livraison' => 'datetime',
    ];
}
