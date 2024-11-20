<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonLivraisonVenteComptant extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_livraison',
        'vente_id',
        'ref_bon',
        'chauffeur_id',
        'vehicule_id',
        'adr_livraison',
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

