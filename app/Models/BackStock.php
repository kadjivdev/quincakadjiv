<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'from_magasin_id',
        'to_magasin_id',
        'montant_total',
        'date_op',
        'observation',
        'user_id',
        'validator',
        'validate_at',
        'client_id'
    ];

    public function provenance() {
        return $this->belongsTo(Magasin::class, 'from_magasin_id');
    }

    public function destination() {
        return $this->belongsTo(Magasin::class, 'to_magasin_id');
    }

    public function lignes() {
        return $this->hasMany(BackStockArticle::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }
}
