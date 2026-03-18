<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug(
                    $model->{$model->slugSourceField()}
                );
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->slugSourceField())) {
                $model->slug = $model->generateUniqueSlug(
                    $model->{$model->slugSourceField()},
                    $model->getKey()
                );
            }
        });
    }

    abstract public function slugSourceField(): string;

    protected function generateUniqueSlug(string $value, $excludeId = null): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;

        $query = static::where('slug', $slug);

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            $query->withTrashed();
        }

        if ($excludeId) {
            $query->where($this->getKeyName(), '!=', $excludeId);
        }

        if (! $query->exists()) {
            return $slug;
        }

        $suffix = 2;
        do {
            $slug = $originalSlug.'-'.$suffix;
            $suffixQuery = static::where('slug', $slug);

            if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
                $suffixQuery->withTrashed();
            }

            if ($excludeId) {
                $suffixQuery->where($this->getKeyName(), '!=', $excludeId);
            }

            $suffix++;
        } while ($suffixQuery->exists());

        return $slug;
    }
}
