<?php

namespace App\Breadcrumbs;

use Illuminate\Support\Collection;
use RuntimeException;

class BreadcrumbTrail
{
    protected Collection $breadcrumbs;

    protected array $callbacks = [];

    public function __construct()
    {
        $this->breadcrumbs = new Collection;
    }

    public function push(string $title, ?string $url = null): self
    {
        $this->breadcrumbs->push((object) [
            'title' => $title,
            'url' => $url,
        ]);

        return $this;
    }

    public function parent(string $name, mixed ...$params): self
    {
        if (! isset($this->callbacks[$name])) {
            throw new RuntimeException("Breadcrumb definition not found: {$name}");
        }

        ($this->callbacks[$name])($this, ...$params);

        return $this;
    }

    public function generate(array $callbacks, string $name, array $params): Collection
    {
        $this->callbacks = $callbacks;
        $this->breadcrumbs = new Collection;

        if (! isset($this->callbacks[$name])) {
            throw new RuntimeException("Breadcrumb definition not found: {$name}");
        }

        ($this->callbacks[$name])($this, ...$params);

        return $this->breadcrumbs;
    }
}
