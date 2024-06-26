<?php

namespace App\Filters\Idea;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class Category extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        if ($this->builderState) {
            return $builder->whereHas('category', function (Builder $query) {
                $state = $this->builderState;
                if (is_array($state)) {
                    $query->whereIn('slug', $this->builderState);
                } else {
                    $query->where('slug', $this->builderState);
                }
            });
        }

        return $builder;
    }
}
