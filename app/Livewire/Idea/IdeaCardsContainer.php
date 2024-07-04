<?php

namespace App\Livewire\Idea;

use App\DataTransferObject\IdeaFilterDto;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Services\Idea\IdeaFilterService;
use Livewire\Component;
use Livewire\WithPagination;

class IdeaCardsContainer extends Component
{
    use WithPagination;

    public $product;

    public $tag;

    public $currentCategory;

    public $status = '';

    public $category = '';

    public $search = '';

    public $otherfilter = 'createdAt';

    public $ideaTitle = '';

    protected $queryString = [
        'status',
        'category',
        'search',
        'otherfilter',
    ];

    protected $listeners = ['ideaQueryStringUpdated', 'ideaQuerySearch', 'searchAsTitle'];

    public function mount(Product $product, ?Category $currentCategory, ?Tag $tag)
    {
        $this->product = $product;
        $this->currentCategory = $currentCategory;
        $this->tag = $tag;
    }

    public function ideaQueryStringUpdated($params)
    {
        $this->category = $params['category'] ?? '';
        $this->status = $params['status'] ?? '';
        $this->otherfilter = $params['otherfilter'] ?? '';
        $this->resetPage();
    }

    public function searchAsTitle($keywords)
    {
        $this->ideaTitle = $keywords;
    }

    public function ideaQuerySearch($keyword)
    {
        $this->search = $keyword;
        $this->resetPage();
        // Clear previous query strings
        $this->category = '';
        $this->status = '';
        $this->otherfilter = '';
    }

    public function loaded()
    {
        // Note: a workaround to highlight current Tag
        $this->dispatch('setActiveTag', $this->tag->id)->to('side-bar.tags-list');
    }

    public function suggestingIdea($title)
    {
        $title = htmlspecialchars_decode($title);
        $title = htmlspecialchars_decode($title); // re-decode
        session()->flash('suggestIdeaTitle', $title);

        return redirect()->route('product.suggest.idea', [$this->product]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function getIdeasProperty()
    {
        $filters = new IdeaFilterDto(
            productId: $this->product->id,
            categorySlug: $this->currentCategory->slug ?? $this->category,
            tagId: $this->tag->id ?? 0,
            statuses: $this->status ? explode('-', $this->status) : [],
            title: $this->search,
            otherFilter: $this->otherfilter
        );

        return (new IdeaFilterService)->filter($filters)
            ->withCount(['spams'])
            ->paginate()
            ->withQueryString();
    }

    public function render()
    {
        $categories = ! $this->currentCategory->exists ? $this->product->categories : [];

        return view('livewire.idea.idea-cards-container', [
            'ideas' => $this->ideas,
            'categories' => $categories,
            'searchTitle' => $this->ideaTitle ?: $this->search,
        ]);
    }
}
