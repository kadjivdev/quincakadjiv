<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonLivraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_livraison',
        'devis_id',
        'code_bon',
        'chauffeur_id',
        'adr_livraison',
        'num_vehicule',
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
