<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public function handle($data, Closure $next);
    public function applyFilter(Builder $builder);
}
