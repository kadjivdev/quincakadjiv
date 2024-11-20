<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_client',
        'code_client',
        'email',
        'address',
        'phone',
        'seuil',
        'categorie',
        'credit_total',
        'acompte_total',
        'statut',
        'departement_id',
        'agent_id'
    ];

    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class, 'devis');
    }

    public function departement() : BelongsTo {
        return $this->belongsTo(Departement::class);
    }

    public function agent() : BelongsTo {
        return $this->belongsTo(Agent::class);
    }

    /* public function vente(): HasMany
    {
        return $this->hasMany(Vente::class, 'ventes');
    } */
}
