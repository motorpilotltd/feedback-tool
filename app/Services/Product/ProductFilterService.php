<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class ProductFilterService
{
    /**
     * Filter products based on the provided search title.
     *
     * @param  string  $searchTitle  The search term for filtering products by name.
     * @return \Illuminate\Database\Eloquent\Builder The filtered query builder.
     */
    public function filter(string $searchTitle = ''): Builder
    {
        $product = app(Pipeline::class)
            ->send([
                'query' => Product::with(['user'])->leftJoin('users', 'users.id', '=', 'products.user_id'),
                'search_field' => [
                    'field' => 'products.name',
                    'value' => $searchTitle,
                ],
            ])
            ->through([
                \App\Filters\Common\SearchField::class,
            ])
            ->thenReturn();

        return $product['query']
            ->withCount(['categories', 'ideas']);
    }
}
