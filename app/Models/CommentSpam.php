<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentSpam extends Model
{
    use HasFactory;

    public $table = 'comment_spam';

    protected $guarded = [];
}
