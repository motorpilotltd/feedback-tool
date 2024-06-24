<?php

namespace App\Models;

use App\Traits\AvoidDuplicateConstraintSoftDelete;
use App\Traits\HasMediaCollectionsTrait;
use App\Traits\WithPerPage;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Models\Permission;

class Product extends Model implements HasMedia
{
    use AvoidDuplicateConstraintSoftDelete,
        CascadeSoftDeletes,
        HasFactory,
        HasMediaCollectionsTrait,
        Sluggable,
        SluggableScopeHelpers,
        SoftDeletes,
        WithPerPage;

    public $guarded = [];

    public function getDuplicateAvoidColumns(): array
    {
        return [
            'name',
            'slug',
        ];
    }

    protected $cascadeDeletes = ['categories', 'tagGroups'];

    protected $casts = [
        'settings' => 'array',
        'links' => 'array',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($product) {
            // Delete product permission
            try {
                if ($permission = Permission::findByName(config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$product->id)) {
                    $permission->delete();
                }

                $product->getMedia('attachments')->each(function ($media) {
                    $media->delete();
                });

            } catch (Exception $e) {
            }
        });

        static::created(function ($product) {
            // Create product's permission definition as well
            Permission::create(['name' => config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$product->id]);
            Category::create([
                'product_id' => $product->id,
                'created_by' => $product->user->id,
                'name' => 'General',
                'description' => '',
            ]);

        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function tagGroups()
    {
        return $this->hasMany(TagGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ideas()
    {
        return $this->hasManyThrough(Idea::class, Category::class)
            ->with('author');
    }

    public function link(): Attribute
    {
        return new Attribute(
            get: fn ($value) => route('product.show', $this)
        );
    }

    public function settings(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                $value = json_decode($value, true);
                $newValue = [
                    'serviceDeskLink' => $value['serviceDeskLink'] ?? '',
                    'hideFromProductList' => $value['hideFromProductList'] ?? false,
                    'hideProductFromBreadcrumbs' => $value['hideProductFromBreadcrumbs'] ?? false,
                    'enableAwaitingConsideration' => $value['enableAwaitingConsideration'] ?? false,
                    'enableSandboxMode' => $value['enableSandboxMode'] ?? false,
                ];

                return $newValue;
            }
        );
    }

    public function permission(): Attribute
    {
        return new Attribute(
            get: fn ($value) => config('const.PERMISSION_PRODUCTS_MANAGE').'.'.$this->id
        );
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
}
