<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\LinksField;
use App\Models\Product;
use App\Services\Product\ProductFilterService;
use App\Traits\Livewire\WithMediaAttachments;
use App\Traits\Livewire\WithTableSorting;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class ProductsTable extends Component
{
    use WireUiActions,
        WithFileUploads,
        WithMediaAttachments,
        WithPagination,
        WithTableSorting;

    public $search = '';

    public $modalTitle;

    public $showModal = false;

    public $showFilters = false;

    public $settings;

    public Collection $links;

    public $linksHasErrors = false;

    public Product $editing; // Wire model binding to model data

    public $productLogo;

    public $newLogo;

    protected $queryString = [];

    protected $listeners = [
        'links-field.links-updated' => 'linksUpdated',
        'links-field.validation-failed' => 'handleLinksValidationFailure',
    ];

    public function mount()
    {
        $this->editing = $this->makeEmptyProduct();
        $this->linksHasErrors = false;
    }

    // When wire model binding, $rules is required
    protected function rules()
    {
        return [
            'editing.name' => [
                'required',
                'min:3',
                Rule::unique('products', 'name')
                    ->when(
                        isset($this->editing->id),
                        fn ($query) => $query->ignore($this->editing->id, 'id')
                    ),
            ],
            'editing.description' => 'required|min:5',
            'settings.hideFromProductList' => '',
            'settings.hideProductFromBreadcrumbs' => '',
            'settings.enableAwaitingConsideration' => '',
            'settings.enableSandboxMode' => '',
            'settings.serviceDeskLink' => 'url',
        ];
    }

    public function linksUpdated($links)
    {
        $this->linksHasErrors = false;
        $this->links = collect($links);
    }

    public function handleLinksValidationFailure($errors)
    {
        $this->linksHasErrors = true;
        // Optionally merge errors into parent's error bag
        foreach ($errors as $key => $messages) {
            foreach ($messages as $message) {
                $this->addError($key, $message);
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function makeEmptyProduct()
    {
        $this->settings = [
            'serviceDeskLink' => '',
            'hideFromProductList' => false,
            'hideProductFromBreadcrumbs' => false,
            'enableAwaitingConsideration' => false,
            'enableSandboxMode' => false,
        ];
        $this->links = collect([]);

        return Product::make();
    }

    /**
     * This removes the line Product::find($id),
     * with Model type hinting as the parameter
     */
    public function edit($productId)
    {
        $this->resetLogo();
        $product = Product::find($productId);
        if ($this->editing->isNot($product)) {
            $this->editing = $product;
        } // Preserved form data when ESC pressed or cancel clicked
        $this->productLogo = $product->getMedia('attachments')->first();
        $this->settings = $product->settings;
        $this->links = collect($product->links);

        // Emit an event to the LinksField component with the product's links
        $this->dispatch('populate-links', links: $this->links)->to(LinksField::class);

        $this->modalTitle = __('text.editproduct', ['name' => $product->name]);
        $this->showModal = true;
    }

    public function deleteDialog($productId, bool $confirm = false)
    {

        $product = Product::find($productId);

        if (! $confirm) {
            $this->dialog()->confirm([
                'title' => __('text.areyousure'),
                'description' => __('text.deleteproduct', ['product' => $product->name]),
                'icon' => 'trash',
                'accept' => [
                    'label' => 'Yes, confirm',
                    'method' => 'deleteDialog',
                    'params' => [$product->id, true],
                ],
                'reject' => [
                    'label' => 'No, cancel',
                ],
            ]);
        } else {
            $product->delete();
            $this->notification()->success(
                $description = __('text.successfullydeleted'),
            );
        }

    }

    public function updatedNewLogo()
    {
        $this->validate([
            'newLogo' => 'image|max:2048', // 2MB Max
        ]);
    }

    public function hydrate()
    {
        $this->resetValidation();
    }

    public function save()
    {
        // Prevent save if links have validation errors
        if ($this->linksHasErrors) {
            $this->notification()->error(
                title: 'Validation Error',
                description: 'Please fix the errors in the links section'
            );

            return;
        }

        $this->validate();
        $this->editing->settings = $this->settings;
        $this->editing->links = $this->links->toArray();
        $this->editing->user_id = auth()->id();
        $this->editing->save();

        // Update ideas with 'new' status when enableAwaitingConsideration was toggled off
        if (! $this->settings['enableAwaitingConsideration']) {
            if ($ideas = $this->editing->ideas->where('status', config('const.STATUS_NEW'))) {
                $ideas->each(function ($idea) {
                    $idea->status = config('const.STATUS_CONSIDERED');
                    $idea->save();
                });
            }
        }

        if (! empty($this->newLogo)) {
            // Delete if there's a current logo
            if (! empty($this->productLogo)) {
                $this->productLogo->delete();
            }
            // Upload new logo
            $this->attachments[] = $this->newLogo;
            $this->storeAttachments($this->editing);
        }

        $this->notification()->success(
            $title = 'Product information',
            $description = __('text.successfullysaved')
        );

        $this->dispatch('product-created');
        $this->showModal = false;
    }

    public function openCreateModal()
    {
        $this->resetLogo();
        // Preserved form data when ESC pressed or cancel clicked
        if ($this->editing->getKey()) {
            $this->editing = $this->makeEmptyProduct();
        }

        $this->modalTitle = __('text.addnewproduct');
        $this->showModal = true;
    }

    public function getProductsProperty()
    {
        return (new ProductFilterService)->filter($this->search)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate()
            ->withQueryString();
    }

    public function deleteLogo()
    {
        if (! empty($this->productLogo)) {
            $this->productLogo->delete();
        }
        $this->resetLogo();
    }

    public function resetLogo()
    {
        $this->newLogo = null;
        $this->productLogo = null;
        $this->linksHasErrors = false;
        $this->dispatch('logoPreviewReset');
    }

    public function render()
    {
        $errorbag = $this->getErrorBag();

        return view('livewire.admin.products-table', [
            'products' => $this->products,
            'errorbag' => $errorbag,
        ]);
    }
}
