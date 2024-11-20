<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_id',
        'date_facture',
        'montant_facture',
        'num_facture',
        'taux_remise',
        'montant_total',
        'montant_regle',
        'statut',
        'client_facture',
        'user_id',
        'facture_type_id',
        'aib',
        'tva',
        'validate_by',
        'validate_at'
    ];

    protected $casts = [
        'date_facture' => 'datetime',
    ];

    public function devis() : BelongsTo {
        return $this->belongsTo(Devis::class, 'devis_id' );
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function articles() : BelongsToMany {
        return $this->belongsToMany(Article::class, 'article_factures', 'article_id');
    }

    public function typeFacture(): BelongsTo
    {
        return $this->belongsTo(FactureType::class, 'facture_type_id');
    }

    public function typeVente(): BelongsTo
    {
        return $this->belongsTo(TypeVente::class, 'type_vente_id');
    }
}
