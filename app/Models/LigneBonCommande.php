<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneBonCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'qte_cmde',
        'article_id',
        'bon_commande_id',
        'unite_mesure_id',
    ];

    public function bonCommande() : BelongsTo {
        return $this->belongsTo(BonCommande::class);
    }
}
