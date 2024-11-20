<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reglement extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant_regle',
        'facture_fournisseur_id',
        'nature_compte_paiement',
        'code',
        'reference',
        'user_id',
        'date_reglement',
        'type_reglement'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_reglement' => 'datetime',
    ];

    public function facture() : BelongsTo {
        return $this->belongsTo(FactureFournisseur::class, 'facture_fournisseur_id');
    }

    public function factureFournisseur()
{
    return $this->belongsTo(FactureFournisseur::class, 'facture_fournisseur_id');
}

}
