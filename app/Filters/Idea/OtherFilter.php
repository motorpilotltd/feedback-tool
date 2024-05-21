<?php

namespace App\Filters\Idea;

use App\Filters\BaseFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Group;

class OtherFilter extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        $filter = trim($this->builderState);
        switch ($filter) {
            case 'recentlyupdated':
                $builder->where('ideas.updated_at', '>=', Carbon::now()->subdays(14));
                $builder->orderBy('ideas.updated_at', 'desc');
                break;
            case 'trending':
                $builder->has('votes');
                $builder->orderBy('ideas.updated_at', 'desc');
                break;
            case 'top':
                $builder->orderBy('votes_count', 'desc');
                break;
            case 'new':
                $builder->where('ideas.created_at', '>=', Carbon::now()->subdays(14));
                $builder->orderBy('ideas.created_at', 'desc');
                break;
            case 'myidea':
                $builder->where('ideas.author_id', auth()->user()->id);
                $builder->orderBy('ideas.created_at', 'desc');
                break;
            default:
                $builder->orderBy('ideas.created_at', 'desc');
                break;
        }
    }


}
