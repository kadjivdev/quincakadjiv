<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonCommande extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_bon_cmd',
        'statut', 'user_id', 'reference'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_bon_cmd' => 'datetime',
    ];

    public function articles() : BelongsToMany {
        return $this->belongsToMany(Article::class, 'ligne_bon_commandes', 'article_id', 'bon_commande_id');
    }

    public function createur() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
