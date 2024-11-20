<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompteFrs extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_op',
        'cle',
        'montant_op',
        'facture_id',
        'fournisseur_id',
        'user_id',
        'type_op',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_op' => 'datetime',
    ];

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(FactureFournisseur::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
