<?php

namespace App\Models;

use App\Models\CommentSpam;
use App\Models\Idea;
use App\Models\User;
use App\Traits\HasMediaCollectionsTrait;
use App\Traits\WithPerPage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;

class Comment extends Model implements HasMedia
{
    use HasFactory,
        WithPerPage,
        HasMediaCollectionsTrait;
    protected $guarded = [];

    protected $touches = ['idea'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['idea'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $withCount = ['spams'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'is_status_update', 'slug');
    }

    public function spams()
    {
        return $this->belongsToMany(User::class, 'comment_spam')->withTimestamps();
    }


}
