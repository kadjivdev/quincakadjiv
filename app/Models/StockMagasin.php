<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMagasin extends Model
{
    use HasFactory;

    protected $table = 'stock_magasins';

    protected $fillable = [
        'article_id',
        'magasin_id',
        'qte_stock',
        'qte_reel',
    ];
}
