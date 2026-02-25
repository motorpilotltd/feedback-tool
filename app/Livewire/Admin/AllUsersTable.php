<?php

namespace App\Livewire\Admin;

use App\Models\Team;
use App\Models\User;
use App\Notifications\AccountCreated;
use App\Traits\Livewire\WithTableSorting;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class AllUsersTable extends Component
{
    use WireUiActions,
        WithPagination,
        WithTableSorting;

    public $searchUser;

    public $showModal = false;

    public $name = '';

    public $email = '';

    public $password = '';

    protected $queryString = [];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'min:8'],
        ];
    }

    public function updatingSearchUser()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $plainPassword = $this->password;
        $passwordWasGenerated = false;

        if (empty($plainPassword)) {
            $plainPassword = Str::random(16);
            $passwordWasGenerated = true;
        }

        $user = DB::transaction(function () use ($plainPassword) {
            return tap(User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($plainPassword),
                'must_change_password' => true,
            ]), function (User $user) {
                $user->ownedTeams()->save(Team::forceCreate([
                    'user_id' => $user->id,
                    'name' => explode(' ', $user->name, 2)[0]."'s Team",
                    'personal_team' => true,
                ]));
            });
        });

        $user->notify(new AccountCreated($user, $passwordWasGenerated ? $plainPassword : null));

        $this->notification()->success(
            $description = __('text.usercreatedsuccess')
        );

        $this->showModal = false;
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
        return view('livewire.admin.all-users-table', [
            'users' => $this->users,
        ]);
    }
}
