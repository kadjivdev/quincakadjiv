<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FactureAncienne extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_facture',
        'num_facture',
        'user_id',
        'client_id',
        'montant_total',
        'montant_facture',
        'facture_type_id',
        'montant_regle',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_facture' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function typeFacture(): BelongsTo
    {
        return $this->belongsTo(FactureType::class, 'facture_type_id');
    }

    public function reglementClients(): HasMany
    {
        return $this->hasMany(ReglementClient::class, 'facture_id');
    }

    public function reglementClientValides(): HasMany
    {
        return $this->hasMany(ReglementClient::class, 'facture_id');
    }
}
