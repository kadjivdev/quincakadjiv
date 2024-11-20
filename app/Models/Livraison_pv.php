<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Livraison_pv extends Model
{
    use HasFactory;
    protected $fillable = [
        'date_liv',
        'ref_liv',
        'chauffeur_id',
        'vehicule_id',
        'cout_revient',
        'magasin_id',
        'user_id',
    ];

    public function approvisionnements(): HasMany
{
    return $this->hasMany(Approvisionnement::class);
}

}


