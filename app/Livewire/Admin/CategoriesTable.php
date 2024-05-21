<?php

namespace App\Livewire\Admin;

use App\Traits\Livewire\WithModelEditing;
use App\Traits\Livewire\WithProductSelection;
use App\Traits\Livewire\WithTableSorting;
use App\Models\Category;
use App\Services\Category\CategoryFilterService;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class CategoriesTable extends Component
{
    use WithPagination, WithProductSelection, WithTableSorting, Actions, WithModelEditing;
    public $showDeleteModal = false;
    public $showEditModal = false;
    public $modalTitle = '';
    public $search = '';

    public Category $editing;

    // When wire model binding, $rules is required
    protected $rules = [
        'editing.name' => 'required|min:5|max:255',
        'editing.description' => '',
    ];

    protected $queryString = [];

    public function mount()
    {
        $this->editing = Category::make();
        $this->sortDirection = 'asc';
    }

    /**
     * This removes the line Product::find($id),
     * with Model type hinting as the parameter
     */
    public function editModal(Category $category)
    {
        $this->setEditing($category);
        $this->modalTitle = __('text.editcategory', ['name' => $category->name]);
        $this->showEditModal = true;
    }

    public function createModal(Category $category)
    {
        $this->setEditing(Category::make());
        $this->modalTitle = __('text.addnewcategory');
        $this->showEditModal = true;
    }

    public function deleteModal(Category $category)
    {
        $this->setEditing($category);
        $this->editing->ideas_count = $category->ideas->count();
        $this->modalTitle = __('text.deleteconfirmation');
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $this->editing->delete();
        $this->notification()->success(
            $title = 'Deleting category',
            $description = __('text.successfullydeleted')
        );
        $this->showDeleteModal = false;
    }

    public function save()
    {
        $this->validate();
        $this->editing->product_id = $this->productId;
        $this->editing->created_by = $this->editing->created_by ?? auth()->id();
        $this->editing->save();
        $this->notification()->success(
            $title = 'Saving category',
            $description = __('text.successfullysaved')
        );
        $this->showEditModal = false;
    }

    public function getCategoriesProperty()
    {
        return (new CategoryFilterService)->filter($this->search)
            ->where('product_id', $this->productId)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate()
            ->withQueryString();
    }

    public function render()
    {

        return view('livewire.admin.categories-table', [
            'categories' => $this->categories,
            'productId' => $this->productId,
        ]);
    }
}
