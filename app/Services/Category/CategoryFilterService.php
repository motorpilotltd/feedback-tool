<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class CategoryFilterService
{
    /**
     * Filter categories based on the provided search name.
     *
     * @param  string  $searchName  The search term for filtering categories by name.
     * @return \Illuminate\Database\Eloquent\Builder The filtered query builder.
     */
    public function filter(string $searchName = ''): Builder
    {
        $category = app(Pipeline::class)
            ->send([
                'query' => Category::with(['product', 'user']),
                'search_field' => [
                    'field' => 'categories.name',
                    'value' => $searchName,
                ],
            ])
            ->through([
                \App\Filters\Common\SearchField::class,
            ])
            ->thenReturn();

        return $category['query']
            ->leftJoin('users', 'users.id', '=', 'categories.created_by')
            ->withCount('ideas');
    }
}
