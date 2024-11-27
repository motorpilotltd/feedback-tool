<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Traits\Livewire\WithTableSorting;
use Carbon\Carbon;
use Illuminate\Pipeline\Pipeline;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class BannedUsersTable extends Component
{
    use WireUiActions,
        WithPagination,
        WithTableSorting;

    public $searchUser;

    public $userId;

    public $showModal;

    protected $rules = [
        'userId' => 'required',
    ];

    protected $queryString = [];

    public function mount()
    {
        $this->sortField = 'name';
    }

    public function suspendUserModal()
    {
        $this->userId = null;
        $this->showModal = true;
    }

    public function suspendUser()
    {
        $user = User::find($this->userId);
        $user->banned_at = Carbon::now();
        $user->save();
        $this->notification()->success(
            $description = __('text.useraccountsuspended', ['user' => $user->name, 'email' => $user->email])
        );
        $this->showModal = false;
    }

    public function unsuspendUserDialog(User $user, $confirm = false)
    {
        if (! $confirm) {
            $this->dialog()->confirm([
                'title' => __('text.areyousure'),
                'description' => __('text.liftusersuspension', ['email' => $user->email, 'user' => $user->name]),
                'icon' => 'trash',
                'accept' => [
                    'label' => __('text.yes_confirm'),
                    'method' => 'unsuspendUserDialog',
                    'params' => [
                        $user,
                        true,
                    ],
                ],
                'reject' => [
                    'label' => __('text.no_cancel'),
                ],
            ]);
        } else {
            if ($user) {
                $user->banned_at = null;
                $this->notification()->success(
                    $description = __('text.useraccountsuspensionlifted', ['user' => $user->name, 'email' => $user->email])
                );
                $user->save();
            } else {
                $this->notification()->error(
                    $description = __('error.actionnotpermitted')
                );
            }
        }
    }

    public function getUsersProperty()
    {
        $usersQuery = app(Pipeline::class)
            ->send([
                'query' => User::whereNotNull('banned_at'),
                'search_field' => [
                    'field' => ['name', 'email'],
                    'value' => $this->searchUser,
                ],
            ])
            ->through([
                \App\Filters\Common\SearchField::class,
            ])
            ->thenReturn();

        return $usersQuery['query']
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate();
    }

    public function render()
    {
        return view('livewire.admin.banned-users-table', [
            'users' => $this->users,
        ]);
    }
}
