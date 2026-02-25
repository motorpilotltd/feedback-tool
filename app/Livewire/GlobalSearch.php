<?php

namespace App\Livewire;

use App\DataTransferObject\IdeaFilterDto;
use App\DataTransferObject\UserFilterDto;
use App\Models\Product;
use App\Models\User;
use App\Services\Category\CategoryFilterService;
use App\Services\Idea\IdeaFilterService;
use App\Services\Product\ProductFilterService;
use App\Services\User\UserFilterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class GlobalSearch extends Component
{
    use WithPagination;

    public string $keywords = '';

    public bool $isFullPage = false;

    public function mount(bool $isFullPage = false, string $keywords = '')
    {
        $this->isFullPage = $isFullPage;
        $this->keywords = $keywords;
    }

    public function getProductsProperty()
    {
        if ($this->keywords && $this->getErrorBag()->count() === 0) {
            $query = (new ProductFilterService)->filter($this->keywords);
            if ($this->isFullPage) {
                return $query->paginate(20, ['*'], 'product_page')->withQueryString();
            }

            return $query->limit(5)->get();
        }

        return collect([]);
    }

    public function getCategoriesProperty()
    {
        if ($this->keywords && $this->getErrorBag()->count() === 0) {
            $query = (new CategoryFilterService)->filter($this->keywords);
            if ($this->isFullPage) {
                return $query->paginate(20, ['*'], 'category_page')->withQueryString();
            }

            return $query->limit(5)->get();
        }

        return collect([]);
    }

    public function getProductAdminsProperty()
    {
        if ($this->keywords && $this->getErrorBag()->count() === 0) {
            $filtersDto = UserFilterDto::fromArray([
                'role' => config('const.ROLE_PRODUCT_ADMIN'),
                'searchFields' => ['name', 'email'],
                'searchValue' => $this->keywords,
            ]);

            $query = (new UserFilterService)->filter($filtersDto)->with('permissions');
            if ($this->isFullPage) {
                $paginator = $query->paginate(20, ['*'], 'prodadmin_page')->withQueryString();
                $paginator->getCollection()->each(function (User $user) {
                    $this->appendUserProductPermissions($user);
                });

                return $paginator;
            }

            return $query->limit(5)->get()->each(function (User $user) {
                $this->appendUserProductPermissions($user);
            });
        }

        return collect([]);
    }

    public function appendUserProductPermissions(User &$user)
    {
        $permissions = $user->getPermissionNames();
        $productsManage = config('const.PERMISSION_PRODUCTS_MANAGE').'.';
        $user->permissionProduct = new Collection;
        foreach ($permissions as $permission) {
            $productId = Str::replace($productsManage, '', $permission);
            if ($product = Product::find($productId)) {
                $user->permissionProduct->push($product->name);
            }
        }
    }

    public function getIdeasProperty()
    {
        if ($this->keywords && $this->getErrorBag()->count() === 0) {
            $filtersDto = IdeaFilterDto::fromArray(['title' => $this->keywords]);

            $query = (new IdeaFilterService)->filter($filtersDto);
            if ($this->isFullPage) {
                return $query->paginate(20, ['*'], 'idea_page')->withQueryString();
            }

            return $query->limit(5)->get();
        }

        return collect([]);
    }

    public function clearKeywords()
    {
        $this->keywords = '';
    }

    public function goToSearchFullPage()
    {
        if (! empty($this->keywords)) {
            // Assuming you have a named route for the search page
            return to_route('frontend.search.index', ['keywords' => $this->keywords]);
        }

        return false;
    }

    public function updatedKeywords()
    {
        $this->resetErrorBag();
        $this->validate([
            'keywords' => 'required|min:3',
        ]);
    }

    public function render()
    {
        return view('livewire.global-search', [
            'products' => $this->products,
            'categories' => $this->categories,
            'productAdmins' => $this->productAdmins,
            'ideas' => $this->ideas,
        ]);
    }
}
