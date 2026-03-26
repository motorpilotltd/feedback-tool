<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Unguarded]
class Vote extends Model
{
    use HasFactory;

    protected $touches = ['idea'];

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }
}
