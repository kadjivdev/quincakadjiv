<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'client',
        'date_fact',
        'client_id',
        'user_id',
        'montant',
        'reference',
        'type_vente_id',
        'date_vente',
        'validated_at',
        'validator_id',
    ];

    public function lignesVente(): HasMany
    {
        return $this->hasMany(VenteLigne::class);
    }

    /*    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    } */

    public function typeVente(): BelongsTo
    {
        return $this->belongsTo(TypeVente::class, 'type_vente_id');
    }

    public function vendeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function acheteur(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function factureVente(){
        return $this->hasOne(FactureVente::class);
    }

    public function encaissements()
    {
        return $this->morphMany(Encaissement::class, 'encaisseable');
    }

    public function clientNom(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
