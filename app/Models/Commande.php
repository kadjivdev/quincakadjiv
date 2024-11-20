<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_cmd',
        'fournisseur_id',
        'bon_commande_id',
        'user_id',
        'reference',
        'statut',
        'transport',
        'charge_decharge',
        'autre',
    ];
/**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_cmd' => 'datetime',
    ];
    public function fournisseur() : BelongsTo {
        return $this->belongsTo(Fournisseur::class);
    }

    public function facture() : HasOne {
        return $this->hasOne(FactureFournisseur::class);
    }

    public function bonCommande() : BelongsTo {
        return $this->belongsTo(BonCommande::class);
    }

    public function commandes() : BelongsToMany {
        return $this->belongsToMany(Article::class, 'ligne_commandes');
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class, 'commande_id');
    }
}
