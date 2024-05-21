<?php

namespace App\Filters\Idea;

use App\Filters\BaseFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Status extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        if ($this->builderState) {
            return $builder->whereIn('status', $this->builderState);
        }
        return $builder;
    }


}
