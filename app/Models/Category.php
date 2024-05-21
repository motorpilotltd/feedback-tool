<?php

namespace App\Models;

use App\Models\Idea;
use App\Models\Product;
use App\Models\User;
use App\Traits\AvoidDuplicateConstraintSoftDelete;
use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;
use App\Traits\WithPerPage;

class Category extends Model
{
    use HasFactory,
        Sluggable,
        SoftDeletes,
        CascadeSoftDeletes,
        AvoidDuplicateConstraintSoftDelete,
        WithPerPage;

    protected $cascadeDeletes = ['ideas'];

    public $guarded = [];

    public function getDuplicateAvoidColumns() : array
    {
        return [
            'slug',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public function link(): Attribute
    {
        return new Attribute(
            get: fn ($value) => route('category.show', $this)
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
                'source' => 'name'
            ]
        ];
    }
}
