<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaSpam extends Model
{
    use HasFactory;

    public $table = 'idea_spam';

    protected $guarded = [];
}
