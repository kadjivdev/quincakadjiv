<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FactureFournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'date_facture',
        'montant_facture',
        'ref_facture',
        'taux_remise',
        'montant_total',
        'montant_regle',
        'statut',
        'fournisseur_id',
        'user_id',
        'facture_type_id',
        'aib',
        'tva',
    ];

    protected $casts = [
        'date_facture' => 'datetime',
    ];

    public function typeFacture(): BelongsTo
    {
        return $this->belongsTo(FactureType::class, 'facture_type_id');
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function reglements(): HasMany
    {
        return $this->hasMany(Reglement::class, 'facture_fournisseur_id');
    }

    public function reglementsValides(): HasMany
    {
        return $this->hasMany(Reglement::class, 'facture_fournisseur_id');
    }
}
