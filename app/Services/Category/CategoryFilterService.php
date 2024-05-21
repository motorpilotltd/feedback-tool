<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Pipeline\Pipeline;

class CategoryFilterService
{
    /**
     * Filter categories based on the provided search name.
     *
     * @param string $searchName The search term for filtering categories by name.
     * @return \Illuminate\Database\Eloquent\Builder The filtered query builder.
     */
    public function filter($searchName = '')
    {
        $category = app(Pipeline::class)
            ->send([
                'query' => Category::with(['user']),
                'search_field' => [
                    'field' => 'categories.name',
                    'value' => $searchName
                ],
            ])
            ->through([
                \App\Filters\Common\SearchField::class,
            ])
            ->thenReturn();
        return $category['query']
            ->with(['product'])
            ->withCount('ideas');
        }
}
