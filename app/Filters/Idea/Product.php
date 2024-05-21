<?php

namespace App\Filters\Idea;

use App\Filters\BaseFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Product extends BaseFilter
{
    public function applyFilter(Builder $builder)
    {
        if ($this->builderState) {
            return $builder->with('author', 'category')
                ->leftJoin('categories as pc1', 'pc1.id', '=', 'ideas.category_id')
                ->leftJoin('products', 'products.id', '=', 'pc1.product_id')
                ->where('products.id', $this->builderState);
        }
        return $builder;
    }


}
