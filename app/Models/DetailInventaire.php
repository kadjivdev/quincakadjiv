<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailInventaire extends Model
{
    use HasFactory;

    protected $table = 'detail_inventaires';

    protected $fillable = [
        'stock_magasin_id',
        'inventaire_id',
        'qte_reel',
        'qte_stock',
    ];
}
