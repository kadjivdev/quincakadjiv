<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackStockArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'qte_back',
        'qte_vrai',
        'prix_unit',
        'article_id',
        'unite_mesure_id',
        'back_stock_id',
    ];

    public function article() {
        return $this->belongsTo(Article::class);
    }

    public function unite_mesure() {
        return $this->belongsTo(UniteMesure::class);
    }
}
