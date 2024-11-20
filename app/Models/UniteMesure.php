<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniteMesure extends Model
{
    use HasFactory;

    protected $fillable = [
        'unite',
        'abbrev',
    ];

    // public function taux() : HasMany {
    //     return $this->hasMany(TauxConversion::class);
    // }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'taux_conversions', 'article_id');
    }

}
