<?php

namespace App\Filters\Idea;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OtherFilter extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        $filter = trim($this->builderState);
        switch ($filter) {
            case 'recentlyupdated':
                $builder->where('ideas.updated_at', '>=', Carbon::now()->subdays(14));
                $builder->orderByDesc('ideas.updated_at');
                break;
            case 'trending':
                $builder->has('votes');
                $builder->orderByDesc('ideas.updated_at');
                break;
            case 'top':
                $builder->orderByDesc('votes_count');
                break;
            case 'new':
                $builder->where('ideas.created_at', '>=', Carbon::now()->subdays(14));
                $builder->orderByDesc('ideas.created_at');
                break;
            case 'myidea':
                $builder->where('ideas.author_id', auth()->user()->id);
                $builder->orderByDesc('ideas.created_at');
                break;
            case 'createdAt':
                $builder->orderByDesc('ideas.created_at');
                break;
            default:
                // No  orderBy should be added in the query
                break;
        }
    }
}
