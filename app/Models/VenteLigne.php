<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenteLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'qte_cmde',
        'qte_livre',
        'article_id',
        'vente_id',
        'prix_unit',
        'unite_mesure_id',
    ];

    public function vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class);
    }
}
