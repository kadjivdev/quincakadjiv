<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'code_article',
        'stock_alert',
        'categorie_id',
        'unite_mesure_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $uuid = Str::uuid();
            $article->code_article = uniqid('ART');
        });
    }

    public function categorie() : BelongsTo {
        return $this->belongsTo(Categorie::class);
    }

    public function uniteBase() : BelongsTo {
        return $this->belongsTo(UniteMesure::class, 'unite_mesure_id');
    }

    public function commandes() : BelongsToMany {
        return $this->belongsToMany(Commande::class, 'ligne_commandes');
    }

    public function fournisseurs() : BelongsToMany {
        return $this->belongsToMany(Fournisseur::class);
    }

    public function point_ventes() : BelongsToMany {
        return $this->belongsToMany(PointVente::class, 'article_point_ventes', 'point_vente_id');
    }

    public function factures() : BelongsToMany {
        return $this->belongsToMany(Facture::class, 'article_factures', 'facture_id');
    }

    public function articleUnites()
    {
        return $this->belongsToMany(UniteMesure::class, 'taux_conversions', 'article_id', 'unite_mesure_id')
            ->withPivot('taux_conversion');
    }

    public function getPivotValueForUnite($uniteMesureId): ?float
    {
        $pivotValue = $this->articleUnites()->wherePivot('unite_mesure_id', $uniteMesureId)->first();
        return $pivotValue ? $pivotValue->pivot->taux_conversion : null;
    }

    public function requetes(){
        return $this->belongsToMany(Requete::class, 'requete_articles');
    }
}
