<?php

namespace App\Filters\Idea;

use App\Filters\BaseFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Tag extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        if ($this->builderState) {
            return $builder->with('tags')
                ->leftJoin('idea_tag', 'idea_tag.idea_id', '=', 'ideas.id')
                ->leftJoin('tags', 'tags.id', '=', 'idea_tag.tag_id')
                ->where('tags.id', $this->builderState);
        }
        return $builder;
    }
}
