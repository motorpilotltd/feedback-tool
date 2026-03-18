<?php

namespace App\Models;

use App\Traits\AvoidDuplicateConstraintSoftDelete;
use App\Traits\HasSlug;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Unguarded]
class Tag extends Model
{
    use AvoidDuplicateConstraintSoftDelete,
        CascadeSoftDeletes,
        HasFactory,
        HasSlug,
        SoftDeletes;

    protected $cascadeDeletes = [];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function ($tag) {
            // Delete tag_idea
            IdeaTag::where('tag_id', $tag->id)->delete();
        });
    }

    public function getDuplicateAvoidColumns(): array
    {
        return [
            'slug',
        ];
    }

    public function slugSourceField(): string
    {
        return 'name';
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function ideas(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class);
    }

    public function tagGroup(): BelongsTo
    {
        return $this->belongsTo(TagGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
