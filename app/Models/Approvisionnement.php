<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approvisionnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_livraison',
        'qte_livre',
        'ligne_commande_id',
        'ligne_supplement_commande_id',
        'user_id',
        'chauffeur_id',
        'vehicule_id',
        'magasin_id',
        'livraison_pv_id',
        'commande_id',
        'unite_mesure_id',
        'validated_at',
        'validator_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_livraison' => 'datetime',
    ];

    public function ligneCommande(): BelongsTo
    {
        return $this->belongsTo(LigneCommande::class);
    }

    public function lieuLivraison(): BelongsTo
    {
        return $this->belongsTo(Magasin::class);
    }
}
