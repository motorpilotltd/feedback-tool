<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Status;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Component;

class IdeasProgress extends Component
{
    public $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function getStatusesProperty()
    {
        $statuses = Status::when(
            ! $this->product->settings['enableAwaitingConsideration'],
            fn (Builder $query) => $query->where('slug', '!=', config('const.STATUS_NEW'))
        )
            ->get();

        $statuses->each(function ($status) {
            // Query the 10 most recent ideas for this status directly, rather
            // than loading every idea for the product into memory and slicing in
            // PHP. (The previous sortBy(['created_at', 'desc']) passed 'desc' as
            // a second sort column, not a direction, so it actually sorted
            // oldest-first; newest-first is the intended order.)
            $status->ideas = $this->product->ideas()
                ->where('ideas.status', $status->slug)
                ->latest('ideas.created_at')
                ->take(10)
                ->get();

            return $status;
        });

        return $statuses;
    }

    public function render()
    {
        return view('livewire.product.ideas-progress', [
            'statuses' => $this->statuses,
        ]);
    }
}
