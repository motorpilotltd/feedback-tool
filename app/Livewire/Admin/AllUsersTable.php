<?php

namespace App\Livewire\Admin;

use WireUi\Traits\Actions;
use Livewire\WithPagination;
use Illuminate\Pipeline\Pipeline;
use Livewire\Component;
use App\Traits\Livewire\WithTableSorting;
use App\Models\User;

class AllUsersTable extends Component
{
    use Actions,
        WithPagination,
        WithTableSorting;

    public $searchUser;
    protected $queryString = [];

    public function updatingSearchUser ()
    {
        $this->resetPage();
    }

    /**
     * Fetch all the users
     */
    public function getUsersProperty()
    {
        $usersQuery = app(Pipeline::class)
            ->send([
                'query' => User::whereNull('banned_at'),
                'search_field' => [
                    'field' => ['name', 'email'],
                    'value' => $this->searchUser
                ],
            ])
            ->through([
                \App\Filters\Common\SearchField::class
            ])
            ->thenReturn();

        return  $usersQuery['query']
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate();
    }

    public function render()
    {
        return view('livewire.admin.all-users-table', [
            'users' => $this->users
        ]);
    }
}
