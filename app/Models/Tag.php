<?php

namespace App\Models;

use App\Traits\AvoidDuplicateConstraintSoftDelete;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use AvoidDuplicateConstraintSoftDelete,
        CascadeSoftDeletes,
        HasFactory,
        Sluggable,
        SluggableScopeHelpers,
        SoftDeletes;

    protected $guarded = [];

    protected $cascadeDeletes = [];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
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

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function ideas()
    {
        return $this->belongsToMany(Idea::class);
    }

    public function tagGroup()
    {
        return $this->belongsTo(TagGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
