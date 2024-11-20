<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactureVente extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id',
        'date_facture',
        'montant_facture',
        'num_facture',
        'taux_remise',
        'montant_total',
        'client_facture',
        'user_id',
        'facture_type_id',
        'aib',
        'tva',
        'montant_regle',
        'statut',

    ];

    protected $casts = [
        'date_facture' => 'datetime',
    ];

    public function vente() : BelongsTo {
        return $this->belongsTo(Vente::class, 'vente_id' );
    }

    // public function typeFacture(): BelongsTo
    // {
    //     return $this->belongsTo(FactureType::class);
    // }

    public function typeVente(): BelongsTo
    {
        return $this->belongsTo(TypeVente::class, 'type_vente_id');
    }

    public function typeFacture(): BelongsTo
    {
        return $this->belongsTo(FactureType::class, 'facture_type_id');
    }
}
