<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivraisonVenteMagasin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vente_ligne_id',
        'qte_livre',
        'bon_vente_id',
        'magasin_id',
        'statut',
        'article_id',
        'prix_unit',
        'unite_mesure_id',
        'bon_livraison_vente_comptant_id',
        'validated_at',
        'validator_id',
    ];



     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'validated_at' => 'datetime',
    ];


    public function venteLigne(): BelongsTo
    {
        return $this->belongsTo(VenteLigne::class, 'vente_ligne_id');
    }

    public function bonVente(): BelongsTo
    {
        return $this->belongsTo(BonVente::class, 'bon_vente_id');
    }

    public function magasin(): BelongsTo
    {
        return $this->belongsTo(Magasin::class, 'magasin_id');
    }
}
