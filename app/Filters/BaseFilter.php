<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class BaseFilter implements Filter
{
    public function handle($state, Closure $next)
    {
        $filterName = $this->filterName();
        if (! array_key_exists($filterName, $state) || empty($state[$filterName]) || ! $state[$filterName]) {
            return $next($state);
        }
        $this->builderState = $state[$filterName];
        $builder = $state['query'];
        $this->applyFilter($builder);

        return $next($state);
    }

    abstract public function applyFilter(Builder $builder);

    protected function filterName()
    {
        return Str::snake(class_basename($this));
    }
}
