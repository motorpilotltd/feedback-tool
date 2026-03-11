<?php

namespace App\Services\Idea;

use App\DataTransferObject\IdeaFilterDto;
use App\Filters\Common\SearchField;
use App\Filters\Idea\Category;
use App\Filters\Idea\OtherFilter;
use App\Filters\Idea\Product;
use App\Filters\Idea\Status;
use App\Filters\Idea\Tag;
use App\Models\Idea;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class IdeaFilterService
{
    /**
     * Filter ideas based on various criteria.
     *
     * @param  IdeaFilterDto  $filter  The filters to be applied
     * @return Builder The filtered query builder.
     */
    public function filter(IdeaFilterDto $filter): Builder
    {
        $idea = app(Pipeline::class)
            ->send([
                'query' => Idea::with(['category', 'ideaStatus']),
                'product' => $filter->productId,
                'category' => $filter->categorySlug,
                'tag' => $filter->tagId,
                'status' => $filter->statuses,
                'search_field' => [
                    'field' => 'title',
                    'value' => $filter->title,
                ],
                'other_filter' => $filter->otherFilter ?: 'default',
            ])
            ->through([
                Product::class,
                Status::class,
                Category::class,
                Tag::class,
                SearchField::class,
                OtherFilter::class,
            ])
            ->thenReturn();

        return $idea['query']
            ->with(['product', 'author'])
            ->addSelect([
                'voted_by_user' => Vote::select('id')
                    ->where('user_id', auth()->id())
                    ->whereColumn('idea_id', 'ideas.id'),
            ]);
    }
}
