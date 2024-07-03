<?php

namespace App\Filters\Idea;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class Product extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        if ($this->builderState) {
            return $builder->leftJoin('categories as category', 'category.id', '=', 'ideas.category_id')
                ->leftJoin('products', 'products.id', '=', 'category.product_id')
                ->leftJoin('statuses as status', 'status.slug', '=', 'ideas.status')
                ->leftJoin('users as addedByUser', 'addedByUser.id', '=', 'ideas.added_by')
                ->where('products.id', $this->builderState);
        }

        return $builder;
    }
}
