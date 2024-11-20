<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_frs',
        'email',
        'address',
        'phone',
    ];

    public function articles() : BelongsToMany {
        return $this->belongsToMany(Article::class, 'article_fournisseurs');
    }

    public function factures() : HasMany {
        return $this->hasMany(FactureFournisseur::class);
    }

    public function reglements() : HasMany {
        return $this->hasMany(Reglement::class);
    }
}
