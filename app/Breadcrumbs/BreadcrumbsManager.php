<?php

namespace App\Breadcrumbs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class BreadcrumbsManager
{
    protected array $callbacks = [];

    public function for(string $name, callable $callback): void
    {
        $this->callbacks[$name] = $callback;
    }

    public function generate(string $name, mixed ...$params): Collection
    {
        $trail = new BreadcrumbTrail;

        return $trail->generate($this->callbacks, $name, $params);
    }

    public function render(string $name, mixed ...$params): View
    {
        $breadcrumbs = $this->generate($name, ...$params);

        return view(config('breadcrumbs.view'), ['breadcrumbs' => $breadcrumbs]);
    }
}
