<?php

namespace App\Models;

use App\Traits\AvoidDuplicateConstraintSoftDelete;
use Carbon\Traits\Cast;
use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagGroup extends Model
{
    use HasFactory,
        Sluggable,
        SoftDeletes,
        CascadeSoftDeletes,
        AvoidDuplicateConstraintSoftDelete;

    protected $guarded = [];
    protected $cascadeDeletes = ['tags'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getDuplicateAvoidColumns() : array
    {
        return [
            'slug',
        ];
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
