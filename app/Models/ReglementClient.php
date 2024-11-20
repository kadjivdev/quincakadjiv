<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglementClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant_regle',
        'client_id',
        'facture_id',
        'code',
        'reference',
        'livraison_directe_id',
        'user_id',
        'date_reglement',
        'type_reglement',
        'validator_id',
        'validated_at',
        'observations',
        'facture_ancienne_id',
        'montant_total_regle'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_reglement' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function encaissements()
    {
        return $this->morphMany(Encaissement::class, 'encaisseable');
    }
}
