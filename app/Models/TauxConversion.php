<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TauxConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'unite_mesure_id',
        'taux_conversion',
        'article_id',
    ];

    // public function uniteMesure() : BelongsTo {
    //     return $this->belongsTo(UniteMesure::class, 'unite_mesure_id');
    // }


}
