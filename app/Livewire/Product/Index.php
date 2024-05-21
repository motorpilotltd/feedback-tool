<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Services\Product\ProductFilterService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $search = '';
    public $viewMode;

    protected $queryString = ['sortField', 'sortDirection'];

    public function mount()
    {
        $this->viewMode = session('view_mode', 'grid');
        $this->search = request()->search ?: '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleViewMode()
    {
        $this->viewMode = ($this->viewMode == 'list')
            ? 'grid'
            : 'list';

        session(['view_mode' => $this->viewMode]);
    }

    public function sortBy($field)
    {
        $this->resetPage();

        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function getProductsProperty()
    {
        return (new ProductFilterService)->filter($this->search)
            ->where('settings->hideFromProductList', false)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate()
            ->withQueryString();
    }

    public function render()
    {
        return view('livewire.product.index', [
            'products' => $this->products
        ]);
    }

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }
}
