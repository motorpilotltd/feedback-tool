<?php

namespace App\Models;

use App\Traits\HasMediaCollectionsTrait;
use App\Traits\WithPerPage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;

class Comment extends Model implements HasMedia
{
    use HasFactory,
        HasMediaCollectionsTrait,
        WithPerPage;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'is_status_update', 'slug');
    }

    public function spams(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_spam')->withTimestamps();
    }
}
