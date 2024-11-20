<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encaissement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'source_id',
        'user_id',
    ];

    public function encaisseable()
    {
        return $this->morphTo();
    }
}
