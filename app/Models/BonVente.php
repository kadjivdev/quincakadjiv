<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BonVente extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id',
        'code_bon',
        'validated_at',
        'validator_id',
    ];

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($bonVente) {
    //         $uuid = Str::uuid();
    //         $bonVente->code_bon = uniqid('BV');
    //     });
    // }

    public function vente() : BelongsTo {
        return $this->belongsTo(Vente::class, 'vente_id');
    }

    public function livraisons() : HasMany {
        return $this->hasMany(LivraisonVenteMagasin::class, 'livraison_vente_magasins', 'bon_vente_id');
    }
}
