<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfert extends Model
{
    use HasFactory;


    protected $table = 'stock_transferts';

    protected $fillable = [
        'article_id',
        'magasin_depart_id',
        'magasin_dest_id',
        'qte_transfert',
        'user_id',
    ];
}
