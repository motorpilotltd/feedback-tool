<?php

namespace App\Livewire\Admin;

use App\Traits\Livewire\WithProductManage;
use App\Traits\Livewire\WithTableSorting;
use App\Models\Product;
use App\Models\User;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class AdminUsersTable extends Component
{
    use Actions,
        WithPagination,
        WithTableSorting,
        WithProductManage;

    public $showModal = false;
    public $userId;
    public $role;
    public $productIds;
    public $superAdmin;
    public $disabledUsersSelect = false;
    public $disabledRoleSelect = false;
    public $searchUser;
    public $searchSuperUser;
    public $selectProduct;

    protected $rules = [
        'userId' => 'required|integer',
        'role' => 'required|string',
    ];

    protected $queryString = [];

    public function mount()
    {
        $this->superAdmin = config('const.ROLE_SUPER_ADMIN');
    }

    public function save()
    {
        // Only require if role is PRODUCT_ADMIN
        $this->rules['productIds'] = $this->role == $this->productAdmin
            ? 'required'
            : '';
        $this->validate();

        $products = $this->productIds ? Product::find($this->productIds) : null;

        $user = User::find($this->userId);

        $messageNote = '';
        $message = '';
        $success = false;
        $textParams = [
            'user' => __('text.useremail', ['user' => $user->name, 'email' => $user->email]),
            'role' => __('text.role:'.$this->role),
        ];
        switch($this->role) {
            case $this->superAdmin:
                // Prevent user to be re-assigned super-admin role
                $isAssignTo = false;
                if($user->hasRole($this->superAdmin)) {
                    $message = __('error.userlreadyrole', $textParams);
                    $success = false;
                } else if($user->hasRole($this->productAdmin)) {
                    // Remove product roles/permissions
                    $isAssignTo = true;
                    $user->removeRole($this->productAdmin);
                    $user->syncPermissions();
                    $messageNote = __('text.noterolerevoked', ['role' => $this->productAdmin]);
                } else {
                    $isAssignTo = true;
                }
                // Proceed assigning to role
                if ($isAssignTo) {
                    $user->assignRole($this->role);
                    $message = __('text.userassignrole', $textParams);
                    $success = true;
                }
                break;
            case $this->productAdmin:
                $permittedProduct = '';
                $alreadyPermittedProduct = '';
                $success = true;
                $message = __('text.permission:changes', $textParams);

                $permissions = [];
                $productManageList = '';

                // Resync Permission
                foreach ($products as $product) {
                    $permissions[] = $this->productsManage . $product->id;
                    $productManageList .= __('text.permission:product:li', ['product' => $product->name]);
                }
                $user->syncPermissions($permissions);

                $message .= __('text.permission:product:permitted', ['items' => $productManageList]);

                // If user was already super admin, revoke
                if ($user->hasRole($this->superAdmin)) {
                    $messageNote = __('text.noterolerevoked', ['role' => $this->superAdmin]);
                    $user->removeRole($this->superAdmin);
                }

                if(!$user->hasRole($this->role)) {
                    $user->assignRole($this->role);
                }

                break;
        }

        if ($success) {
            $this->notification()->success(
                $title = '',
                $description = $message .  $messageNote,
            );
            $this->dispatch('accordion:set:activeItem', Str::plural($this->role));
        } else {
            $this->notification()->error(
                $title = '',
                $description = $message .  $messageNote,
            );
        }

        $this->reset(['userId', 'disabledUsersSelect']);
        $this->showModal = false;
    }

    public function updatingSearchUser ()
    {
        $this->resetPage();
    }

    public function updatingSearchSuperUser ()
    {
        $this->resetPage();
    }

    public function updatingSelectProduct ()
    {
        $this->resetPage();
    }

    public function addRolePermissionModal($userId = 0)
    {
        $role = '';
        $productIds = [];
        $this->reset(['userId', 'disabledUsersSelect']);
        if ($userId) {
            $user = User::find($userId);
            $role = $user->getRoleNames()->first();
            $productIds = $user->getPermissionNames()->map(function ($permission) {
                return Str::contains($permission, $this->productsManage)
                    ? (int) Str::replace($this->productsManage, '', $permission)
                    : $permission;
            });

            $this->disabledUsersSelect = true;
        }
        $this->userId = $userId;
        if ($this->authUser->hasRole($this->productAdmin)) {
            $role = $this->productAdmin;
            $this->disabledRoleSelect = true;
        }
        $this->role = $role;
        $this->productIds = $productIds;
        $this->showModal = true;
    }

    public function revokeDialog(User $user, $role = null, $permission = null, bool $confirm = false)
    {
        if (Str::isJson($permission)) {
            $permission = json_decode($permission, true);
        }
        $message = __('error:failedtorevoke');
        $success = false;
        $userEmailText = __('text.useremail', ['user' => $user->name, 'email' => $user->email]);
        if (!$confirm) {
            $confirmDesc = '';
            if (!empty($role)) {
                $confirmDesc = __('text.confirmrevoke:role', ['role' => $role, 'user' => $userEmailText]);
            } else if (!empty($permission)) {
                $confirmDesc = __('text.confirmrevoke:product:permission', [
                    'product' => $permission['product']['name'] ?? __('text.unknown'),
                    'role' => $this->productAdmin,
                    'user' => $userEmailText
                ]);
            }

            $this->dialog()->confirm([
                'title'       => __('text.areyousure'),
                'description' => $confirmDesc,
                'icon'        => 'trash',
                'accept'      => [
                    'label'  => __('text.yes_confirm'),
                    'method' => 'revokeDialog',
                    'params' => [
                        $user,
                        $role,
                        $permission,
                        true
                    ],
                ],
                'reject' => [
                    'label'  => __('text.no_cancel'),
                ],
            ]);
        } else {
            if (!empty($role) && $user->hasRole($role)) {
                $user->removeRole($role);
                $user->syncPermissions();
                $success = true;
                $message = __('text.userrevokesuccess:role', [
                    'role' => $role,
                    'user' => $userEmailText
                ]);
            } else if (!empty($permission)) {

                $user->revokePermissionTo($permission['permission']);
                $success = true;
                $message = __('text.userrevokesuccess:product:permission', [
                    'product' => $permission['product']['name'] ?? __('text.unknown'),
                    'user' => $userEmailText
                ]);
            }

            if ($success) {
                $this->notification()->success(
                    $description = $message
                );
            } else {
                $this->notification()->error(
                    $description = $message
                );
            }

        }
    }

    public function render()
    {
        $products = $this->getProducts()->get();

        $productPermissionsIds = $products->pluck('id')
            ->when(!empty($this->selectProduct), function ($collection) {
                return $collection->reject(function($element){
                    return $element !== $this->selectProduct;
                });
            })
            ->map(function($id) {
                return $this->productsManage . $id;
            });

        $usersQuery = app(Pipeline::class)
            ->send([
                'query' => User::permission($productPermissionsIds),
                'search_field' => [
                    'field' => ['name', 'email'],
                    'value' => $this->searchUser
                ],
            ])
            ->through([
                \App\Filters\Common\SearchField::class
            ])
            ->thenReturn();

        // Product admin lists for table
        $productAdmins = $usersQuery['query']
            ->with('permissions')
            ->paginate()
            ->through(function(User $user) {
                $user->permissionProduct = new Collection();
                $authUserPermissionProductIds = $this->authUserPermissionProductIds;
                foreach ($user->getPermissionNames() as $permission) {
                    $productId = Str::replace($this->productsManage, '', $permission);
                    // Product Admin: Do not show permission from other products
                    if ($authUserPermissionProductIds->isNotEmpty() && !$authUserPermissionProductIds->contains($productId)) {
                        continue;
                    }
                    if ($product = Product::find($productId)) {
                        $user->permissionProduct->push([
                            'permission' => $permission,
                            'product' => $product
                        ]);
                    }
                }
                return $user;
            });

        // Super admin list for table
        $superAdmins = collect([]);
        if ($this->authUser->getRoleNames()->first() === $this->superAdmin) {
            $usersQuery = app(Pipeline::class)
                ->send([
                    'query' => User::role($this->superAdmin),
                    'search_field' => [
                        'field' => ['name', 'email'],
                        'value' => $this->searchSuperUser
                    ],
                ])
                ->through([
                    \App\Filters\Common\SearchField::class
                ])
                ->thenReturn();

            $superAdmins = $usersQuery['query']->paginate();
        }

        // Role option usage
        $rolesOption = new Collection();
        if($this->authUser->hasRole($this->superAdmin)) {
            $rolesOption->push(['name' => 'Super Admin',  'id' => $this->superAdmin, 'description' => 'Access to everything...']);
        }
        $rolesOption->push(['name' => 'Product Admin', 'id' => $this->productAdmin, 'description' => 'Manage a specific product only']);

        return view('livewire.admin.admin-users-table', [
            'superadmins' => $superAdmins,
            'productadmins' => $productAdmins,
            'products' => $products,
            'rolesOption' => $rolesOption
        ]);
    }
}
