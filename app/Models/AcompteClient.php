<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcompteClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'montant_acompte',
        'reference',
        'reglement_client_id',
        'user_id',
        'type_reglement',
        'code',
        'observation_acompte_client',
        'date_op'
    ];

    public function client() : BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function encaissements()
    {
        return $this->morphMany(Encaissement::class, 'encaisseable');
    }
}
