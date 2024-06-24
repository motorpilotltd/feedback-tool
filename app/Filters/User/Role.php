<?php

namespace App\Filters\User;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class Role extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        // Assuming $this->builderState is the role you want to filter by
        if ($this->builderState) {
            return $builder->role($this->builderState);
        }

        return $builder;

    }
}
