<?php

namespace App\Livewire\Admin;

use App\DataTransferObject\IdeaFilterDto;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Product;
use App\Models\Status;
use App\Services\Idea\IdeaFilterService;
use App\Traits\Livewire\WithDispatchNotify;
use App\Traits\Livewire\WithModelEditing;
use App\Traits\Livewire\WithProductSelection;
use App\Traits\Livewire\WithTableSorting;
use Livewire\Component;
use Livewire\WithPagination;

class IdeasTable extends Component
{
    use WithDispatchNotify, WithModelEditing, WithPagination, WithProductSelection, WithTableSorting;

    public $showCalculateModal = false;

    public $selectCategories = [];

    public $selectedProduct = 0;

    public $selectedCategory = 0;

    public $selectProducts = [];

    public $showEditModal = false;

    public $showMoveModal = false;

    public $showFilters = false;

    public $calcNumbers = [0, 1, 2, 3, 5, 8, 13, 21];

    public $modalTitle;

    public $selectAll = false;

    public $selected = [];

    public $calcData = [
        'business_value' => 0,
        'time_criticality' => 0,
        'complexity' => 0,
    ];

    public $filters = [
        'search' => '',
        'statuses' => [],
        'categories' => [],
    ];

    public $wsjf = 0;

    public Idea $editing; // Wire model binding to model data

    protected $queryString = [];

    protected $listeners = [
        'productUpdated' => 'resetCheckbox',
    ];

    public function mount()
    {
        $this->editing = $this->makeEmptyIdea();
        $this->selectProducts = Product::all();
    }

    // When wire model binding, $rules is required
    // protected $rules = [];
    protected function rules()
    {
        $categories = $this->moveCategories->pluck('id');

        return [
            'calcData.*' => [function ($attribute, $value, $fail) {
                if (! in_array($value, $this->calcNumbers)) {
                    $fail(__('error.invalidvalue'));
                }
            }],
            'selectedCategory' => ['required', 'integer', function ($attribute, $value, $fail) use ($categories) {
                if (! in_array($value, $categories->toArray())) {
                    $fail(__('error.invalidcategoryvalue'));
                }
            }],
        ];
    }

    public function updatingFilters()
    {
        $this->resetCheckbox();
        $this->resetPage();
    }

    public function resetCheckbox()
    {
        $this->selected = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        $this->selected = $value
            ? $this->ideas->pluck('id')->map(fn ($id) => (string) $id)
            : [];
    }

    public function updatedCalcData()
    {

        $data = $this->calcData;
        foreach ($data as $val) {
            if (empty($val)) {
                $this->wsjf = 0;

                return;
            }
        }
        $wsjf = ($data['business_value'] + $data['time_criticality']) / $data['complexity'];
        $this->wsjf = round($wsjf, 2);
    }

    public function makeEmptyIdea()
    {
        return Idea::make();
    }

    public function exportCsv()
    {
        $ideas = $this->ideas;

        return response()->streamDownload(function () use ($ideas) {
            $ideasTable = $ideas->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'Title' => $item->title,
                    'Description' => $item->content,
                    'Category' => $item->category->name,
                    'Pinned Comment' => $item->pinnedComment->content ?? '',
                    'Status' => $item->ideaStatus->name,
                    'Project Status' => $item->project_status,
                    'Date Added' => $item->created_at->format('l, F jS Y \a\t h:i A'),
                    'No. of Votes' => $item->votes_count,
                    'Business Value' => $item->business_value,
                    'Time Criticality' => $item->time_criticality,
                    'Complexity' => $item->complexity,
                    'WSJF' => $item->wsjf,
                    'Tags' => $item->tags->pluck('name')->implode(', '),
                    'Author' => $item->author->name ?? '',
                    'Submitted By' => $item->addedBy->name ?? '',
                ];
            });

            if (! empty($this->selected)) {
                echo $ideasTable->whereIn('id', $this->selected)->toCsv();
            } else {
                echo $ideasTable->toCsv();
            }
        }, 'Export-ideas_'.strtolower(now()->format('m-d-Y')).'.csv');
    }

    public function hydrate()
    {
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();
        $this->editing->settings = $this->settings;
        $this->editing->user_id = auth()->id();
        $this->editing->save();

        $this->dispatchNotifySuccess(__('text.successfullysaved'));
        $this->showEditModal = false;
    }

    public function saveCalculate()
    {
        $this->validateOnly('calcData.*');
        $this->editing->business_value = $this->calcData['business_value'];
        $this->editing->time_criticality = $this->calcData['time_criticality'];
        $this->editing->complexity = $this->calcData['complexity'];
        $this->editing->wsjf = $this->wsjf;
        $this->editing->save();

        $this->dispatchNotifySuccess(__('text.successfullysaved'));
        $this->showCalculateModal = false;
    }

    public function updatedSelectedProduct()
    {
        // Update categories selection
        $this->selectCategories = $this->moveCategories;
        $this->selectedCategory = 0;
    }

    public function calculate(Idea $idea)
    {
        $this->setEditing($idea);
        $this->calcData['business_value'] = $idea->business_value;
        $this->calcData['time_criticality'] = $idea->time_criticality;
        $this->calcData['complexity'] = $idea->complexity;
        $this->wsjf = $idea->wsjf;

        $this->modalTitle = __('Calculate').' - '.$idea->title;
        $this->showCalculateModal = true;
    }

    public function edit(Idea $idea)
    {
        $this->showEditModal = true;
    }

    public function move(Idea $idea)
    {
        $this->setEditing($idea);

        $this->modalTitle = __('Move').' - '.$idea->title;
        $this->showMoveModal = true;
        $this->selectedProduct = $idea->category->product_id;
        $this->selectedCategory = $idea->category_id;
        $this->selectCategories = $this->moveCategories;
    }

    public function saveMove()
    {
        $this->validateOnly('selectedCategory');

        $this->editing->category_id = $this->selectedCategory;
        $this->editing->save();

        $this->dispatchNotifySuccess(__('text.successfullymovedidea'));
        $this->showMoveModal = false;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getMoveCategoriesProperty()
    {
        return Category::where('product_id', $this->selectedProduct)->get();
    }

    /**
     * $this->ideas accessible within this component
     */
    public function getIdeasProperty()
    {
        $filters = $this->filters;
        $filters['productId'] = $this->productId;
        $filters['categorySlug'] = $filters['categories'];
        $filters['title'] = $filters['search'];

        $filtersDto = IdeaFilterDto::fromArray($filters);

        return (new IdeaFilterService)->filter($filtersDto)->with('tags', 'pinnedComment', 'addedBy');
    }

    public function render()
    {
        $sortField = $this->sortField ?? 'id';
        $ideas = [];
        if ($this->productId) {
            $ideas = $this->ideas
                ->orderBy($sortField, $this->sortDirection)
                ->paginate()
                ->withQueryString();
        }

        return view('livewire.admin.ideas-table', [
            'ideas' => $ideas,
            'statuses' => Status::all(),
            'categories' => Category::where('product_id', $this->productId)->get(),
        ]);
    }
}
