<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventaire extends Model
{
    use HasFactory;

    protected $table = 'inventaires';

    protected $casts = [
        'date_inventaire' => 'datetime',
        'validated_at' => 'datetime',
    ];

    protected $fillable = [
        'magasin_id',
        'date_inventaire',
        'user_id',
        'validated_at',
        'validator_id',
    ];

    public function auteur() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function magasin() : BelongsTo {
        return $this->belongsTo(Magasin::class, 'magasin_id');
    }

    public function details() : HasMany {
        return $this->hasMany(DetailInventaire::class);
    }
}
