<?php

namespace App\Traits\Livewire;

use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait WithProductManage
{
    public Collection $authUserPermissionProductIds;

    public $authUser;

    public $productsManage;

    public $productAdmin;

    public function mountWithProductManage()
    {
        $this->authUser = auth()->user();
        $this->productsManage = config('const.PERMISSION_PRODUCTS_MANAGE').'.';
        $this->productAdmin = config('const.ROLE_PRODUCT_ADMIN');
        $this->authUserPermissionProductIds = $this->authUser->getPermissionNames()->map(function ($permission) {
            return Str::replace($this->productsManage, '', $permission);
        });
    }

    public function getProducts()
    {
        $products = Product::select('id', 'name')->orderBy('name')
            ->when(
                $this->authUser->getRoleNames()->first() == $this->productAdmin,
                function (Builder $query) {
                    return $query->whereIn('id', $this->authUserPermissionProductIds);
                }
            );

        return $products;
    }
}
