<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\User;
use App\Traits\Livewire\WithProductManage;
use App\Traits\Livewire\WithTableSorting;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class AdminUsersTable extends Component
{
    use WireUiActions,
        WithPagination,
        WithProductManage,
        WithTableSorting;

    public $showModal = false;

    public $userId;

    public $roles = [];

    public $productIds;

    public $superAdmin;

    public $disabledUsersSelect = false;

    public $disabledRoleSelect = false;

    public $searchUser;

    public $searchSuperUser;

    public $selectProduct;

    protected $rules = [
        'userId' => 'required|integer',
        'roles.*' => 'required|string',
    ];

    protected $queryString = [];

    public function mount()
    {
        $this->superAdmin = config('const.ROLE_SUPER_ADMIN');
    }

    public function save()
    {
        // Only require if role is PRODUCT_ADMIN
        $this->rules['productIds'] = in_array($this->productAdmin, $this->roles)
            ? 'required'
            : '';
        $this->validate();

        $products = $this->productIds ? Product::find($this->productIds) : null;

        $user = User::find($this->userId);

        $messageNote = [];
        $message = [];
        $success = false;

        // Determine to assign role/permission
        foreach ($this->roles as $role) {
            $textParams = [
                'user' => __('text.useremail', ['user' => $user->name, 'email' => $user->email]),
                'role' => __('text.role:'.$role),
            ];
            switch ($role) {
                case $this->superAdmin:
                    // Proceed assigning to role
                    if (! $user->hasRole($this->superAdmin)) {
                        $user->assignRole($role);
                        $message[] = __('text.userassignrole', $textParams);
                        $success = true;
                    }
                    break;
                case $this->productAdmin:
                    $success = true;
                    $message[] = __('text.permission:changes', $textParams);

                    $permissions = [];
                    $productManageList = '';

                    // Resync Permission
                    foreach ($products as $product) {
                        $permissions[] = $this->productsManage.$product->id;
                        $productManageList .= __('text.permission:product:li', ['product' => $product->name]);
                    }
                    $user->syncPermissions($permissions);

                    $message[] = __('text.permission:product:permitted', ['items' => $productManageList]);

                    if (! $user->hasRole($role)) {
                        $user->assignRole($role);
                    }
                    break;
                default:
                    break;
            }
        } // end of foreach

        // Determine which permission is to remove
        $currentRoles = $user->getRoleNames()->toArray();
        foreach ($currentRoles as $currentRole) {
            if (! in_array($currentRole, $this->roles)) {
                switch ($currentRole) {
                    case $this->superAdmin:
                        $messageNote[] = __('text.noterolerevoked', ['role' => $this->superAdmin]);
                        $user->removeRole($this->superAdmin);
                        break;
                    case $this->productAdmin:
                        $messageNote[] = __('text.noterolerevoked', ['role' => $this->superAdmin]);
                        $user->removeRole($this->productAdmin);
                        $user->syncPermissions();
                        break;
                    default:
                        break;
                }
            }
        }

        $message = $message ? implode('<br>', $message) : '';
        $messageNote = $messageNote ? implode('<br>', $messageNote) : '';
        if ($success) {
            $this->notification()->success(
                $title = '',
                $description = $message.$messageNote,
            );
        } else {
            $this->notification()->error(
                $title = '',
                $description = $message.$messageNote,
            );
        }

        $this->reset(['userId', 'disabledUsersSelect']);
        $this->showModal = false;
    }

    public function updatingSearchUser()
    {
        $this->resetPage();
    }

    public function updatingSearchSuperUser()
    {
        $this->resetPage();
    }

    public function updatingSelectProduct()
    {
        $this->resetPage();
    }

    public function addRolePermissionModal($userId = 0)
    {
        $this->userId = 0;
        $this->roles = [];
        $this->productIds = [];
        $this->reset(['userId', 'disabledUsersSelect']);
        if ($userId) {
            $this->loadUserRolePermissions($userId);
            $this->disabledUsersSelect = true;
        }
        $this->userId = $userId;

        if ($this->authUser->hasRole($this->productAdmin) && ! $this->authUser->hasRole($this->superAdmin)) {
            $this->roles[] = $this->productAdmin;
            $this->disabledRoleSelect = true;
        }
        $this->showModal = true;
    }

    private function loadUserRolePermissions($userId = 0)
    {
        if ($user = User::find($userId)) {
            $this->roles = $user->getRoleNames()->toArray();
            $this->productIds = $user->getPermissionNames()->map(function ($permission) {
                return Str::contains($permission, $this->productsManage)
                    ? (int) Str::replace($this->productsManage, '', $permission)
                    : $permission;
            });
        }
    }

    public function revokeDialog(User $user, $role = null, $permission = null, bool $confirm = false)
    {
        if (Str::isJson($permission)) {
            $permission = json_decode($permission, true);
        }
        $message = __('error:failedtorevoke');
        $success = false;
        $userEmailText = __('text.useremail', ['user' => $user->name, 'email' => $user->email]);
        if (! $confirm) {
            $confirmDesc = '';
            if (! empty($role)) {
                $confirmDesc = __('text.confirmrevoke:role', ['role' => $role, 'user' => $userEmailText]);
            } elseif (! empty($permission)) {
                $confirmDesc = __('text.confirmrevoke:product:permission', [
                    'product' => $permission['product']['name'] ?? __('text.unknown'),
                    'role' => $this->productAdmin,
                    'user' => $userEmailText,
                ]);
            }

            $this->dialog()->confirm([
                'title' => __('text.areyousure'),
                'description' => $confirmDesc,
                'icon' => 'trash',
                'accept' => [
                    'label' => __('text.yes_confirm'),
                    'method' => 'revokeDialog',
                    'params' => [
                        $user,
                        $role,
                        $permission,
                        true,
                    ],
                ],
                'reject' => [
                    'label' => __('text.no_cancel'),
                ],
            ]);
        } else {
            if (! empty($role) && $user->hasRole($role)) {
                $user->removeRole($role);
                $user->syncPermissions();
                $success = true;
                $message = __('text.userrevokesuccess:role', [
                    'role' => $role,
                    'user' => $userEmailText,
                ]);
            } elseif (! empty($permission)) {

                $user->revokePermissionTo($permission['permission']);
                $success = true;
                $message = __('text.userrevokesuccess:product:permission', [
                    'product' => $permission['product']['name'] ?? __('text.unknown'),
                    'user' => $userEmailText,
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

    public function loadSelectedUserId()
    {
        $this->loadUserRolePermissions($this->userId);
    }

    public function render()
    {
        $products = $this->getProducts()->get();

        $productPermissionsIds = $products->pluck('id')
            ->when(! empty($this->selectProduct), function ($collection) {
                return $collection->reject(function ($element) {
                    return $element !== $this->selectProduct;
                });
            })
            ->map(function ($id) {
                return $this->productsManage.$id;
            });

        $usersQuery = app(Pipeline::class)
            ->send([
                'query' => User::permission($productPermissionsIds),
                'search_field' => [
                    'field' => ['name', 'email'],
                    'value' => $this->searchUser,
                ],
            ])
            ->through([
                \App\Filters\Common\SearchField::class,
            ])
            ->thenReturn();

        // Product admin lists for table
        $productAdmins = $usersQuery['query']
            ->with('permissions')
            ->paginate()
            ->through(function (User $user) {
                $user->permissionProduct = new Collection;
                $authUserPermissionProductIds = $this->authUserPermissionProductIds;
                foreach ($user->getPermissionNames() as $permission) {
                    $productId = Str::replace($this->productsManage, '', $permission);
                    // Product Admin: Do not show permission from other products
                    if (! $this->authUser->hasRole($this->superAdmin) && ($authUserPermissionProductIds->isNotEmpty() && ! $authUserPermissionProductIds->contains($productId))) {
                        continue;
                    }
                    if ($product = Product::find($productId)) {
                        $user->permissionProduct->push([
                            'permission' => $permission,
                            'product' => $product,
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
                        'value' => $this->searchSuperUser,
                    ],
                ])
                ->through([
                    \App\Filters\Common\SearchField::class,
                ])
                ->thenReturn();

            $superAdmins = $usersQuery['query']->paginate();
        }

        // Role option usage
        $rolesOption = new Collection;
        if ($this->authUser->hasRole($this->superAdmin)) {
            $rolesOption->push(['name' => 'Super Admin',  'id' => $this->superAdmin, 'description' => 'Access to everything...']);
        }
        $rolesOption->push(['name' => 'Product Admin', 'id' => $this->productAdmin, 'description' => 'Manage a specific product only']);

        return view('livewire.admin.admin-users-table', [
            'superadmins' => $superAdmins,
            'productadmins' => $productAdmins,
            'products' => $products,
            'rolesOption' => $rolesOption,
        ]);
    }
}
