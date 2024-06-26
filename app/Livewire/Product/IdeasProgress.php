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
        $ideas = $this->product->ideas;

        $statuses = Status::when(
            ! $this->product->settings['enableAwaitingConsideration'],
            fn (Builder $query) => $query->where('slug', '!=', config('const.STATUS_NEW'))
        )
            ->get();

        $statuses->each(function ($status) use ($ideas) {
            $lists = $ideas->where('status', $status->slug);
            $status->ideas = $lists->sortBy(['created_at', 'desc'])->take(10);

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
