<?php

namespace App\Models;

use App\Models\Idea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $touches = ['idea'];

    protected $guarded = [];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}
