<?php

namespace App\Livewire\Idea;

use App\Traits\Livewire\WithDispatchNotify;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Status;
use Livewire\Component;

class Filters extends Component
{
    use WithDispatchNotify;

    public $selectedStatuses = [];
    public $selectedCategory = '';
    public $categories = [];
    public $statuses;
    public $selectedFilter;
    public $filters = [
        'recentlyupdated',
        'trending',
        'top',
        'new',
    ];

    public function mount($categories, $product)
    {
        if (!request()->routeIs('category.show')) {
            $this->categories = $categories;
            $this->selectedCategory = request()->category ?: '';
        }
        $this->statuses = Status::when(
            !$product->settings['enableAwaitingConsideration'],
            fn (Builder $query) => $query->where('slug', '!=', config('const.STATUS_NEW'))
        )
        ->get();
        $this->selectedStatuses = request()->status ? explode('-', request()->status): [];
        $this->selectedFilter = request()->otherfilter ? request()->otherfilter: '';

        // Add 'my idea' filter to filter logged in user's idea(s)
        if (!auth()->guest()) {
            $this->filters[] = 'myidea';
        }
    }

    public function getIdeasByFilter($filter)
    {
        // Validate filter
        if (!in_array($filter, $this->filters)) {
            $this->dispatchNotifyWarning(__('error.invalidfilter'));
        } else {
            $filter = $this->selectedFilter !== $filter ? $filter : '' ;
            $this->selectedCategory = '';
            $this->selectedStatuses = [];
            $this->selectedFilter = $filter;
            $this->updated();
        }
    }
    public function allStatus()
    {
        $this->selectedStatuses = $this->statuses->pluck(['slug'])->toArray();
        $this->updated();
    }

    public function clearStatus()
    {
        $this->selectedStatuses = [];
        $this->updated();
    }

    public function updated()
    {
        $queryParams = [
            'status' => !empty($this->selectedStatuses) ? implode('-', $this->selectedStatuses) : '',
            'category' => $this->selectedCategory,
            'otherfilter' => $this->selectedFilter,
        ];
        $this->dispatch('ideaQueryStringUpdated', $queryParams);
    }

    public function render()
    {
        return view('livewire.idea.filters');
    }
}
