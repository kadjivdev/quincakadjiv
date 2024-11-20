<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivraisonDirecte extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_livraison',
        'ref_livraison',
        'qte_livre',
        'prix_vente',
        'ligne_commande_id',
        'user_id',
        'client_id',
        'unite_mesure_id',
        'validated_at',
        'validator_id',
        'montant_total',
        'montant_regle',
        'montant_facture',
        'num_facture',
        'facture_type_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_livraison' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function ligneCommande(): BelongsTo
    {
        return $this->belongsTo(LigneCommande::class, 'validator_id');
    }

    public function typeFacture(): BelongsTo
    {
        return $this->belongsTo(FactureType::class, 'facture_type_id');
    }
}
