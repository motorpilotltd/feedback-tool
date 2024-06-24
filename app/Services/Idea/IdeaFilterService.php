<?php

namespace App\Services\Idea;

use Illuminate\Database\Eloquent\Builder;
use App\DataTransferObject\IdeaFilterDto;
use App\Models\Idea;
use App\Models\Vote;
use Illuminate\Pipeline\Pipeline;

class IdeaFilterService
{
    /**
     * Filter ideas based on various criteria.
     *
     * @param  IdeaFilterDto  $filter  The filters to be applied
     * @return \Illuminate\Database\Eloquent\Builder The filtered query builder.
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
                \App\Filters\Idea\Product::class,
                \App\Filters\Idea\Status::class,
                \App\Filters\Idea\Category::class,
                \App\Filters\Idea\Tag::class,
                \App\Filters\Common\SearchField::class,
                \App\Filters\Idea\OtherFilter::class,
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
