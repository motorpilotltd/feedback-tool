<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Traits\GenerateModelLivewireKeyTrait;
use App\Traits\HasMediaCollectionsTrait;
use App\Traits\WithPerPage;
use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Znck\Eloquent\Traits\BelongsToThrough;

class Idea extends Model implements HasMedia
{
    use HasFactory,
        Sluggable,
        SoftDeletes,
        WithPerPage,
        BelongsToThrough,
        CascadeSoftDeletes,
        HasMediaCollectionsTrait,
        GenerateModelLivewireKeyTrait;

    protected $cascadeDeletes = ['comments', 'votes', 'tags'];

    protected $guarded = [];

    protected $withCount = ['comments', 'votes'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Deleting associated media to the idea
        static::deleting(function ($idea) {
            $idea->getMedia('attachments')->each(function ($media) {
                $media->delete();
            });
        });
    }

    public function pinnedComment()
    {
        return $this->belongsTo(Comment::class, 'sticky_comment_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ideaStatus()
    {
        return $this->belongsTo(Status::class, 'status', 'slug');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function spams()
    {
        return $this->belongsToMany(User::class, 'idea_spam')->withTimestamps();
    }

    public function votes()
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function product()
    {
        return $this->belongsToThrough(Product::class, Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ideaLink(): Attribute
    {
        return new Attribute(
            get: fn ($value) => route('idea.show', $this)
        );
    }

    public function productId(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->category->product_id ?? 0
        );
    }

    public function statusClass(): Attribute
    {
        return new Attribute(
            get: fn ($value) => 'status-' . Str::kebab($this->status)
        );
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
