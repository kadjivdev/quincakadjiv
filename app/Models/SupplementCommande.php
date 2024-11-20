<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplementCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'user_id',
        'date_cmd',
        'unite_mesure_id',
        'statut',
    ];

    public function commande() : BelongsTo {
        return $this->belongsTo(Commande::class);
    }
}
