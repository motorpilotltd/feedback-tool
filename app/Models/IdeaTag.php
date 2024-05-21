<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaTag extends Model
{
    use HasFactory;

    public $table = 'idea_tag';

    protected $guarded = [];
}
