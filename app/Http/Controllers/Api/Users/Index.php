<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Index extends Controller
{
    public function __invoke(Request $request): Collection
    {
        $users = User::query()
            ->select('id', 'name', 'email', 'profile_photo_path')
            ->orderBy('name')
            ->whereNot(function ($query) {
                // Prevent current logged in user to select itself
                $query->where('id', auth()->user()->id);
            })
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->get()
            ->map(function (User $user) {
                // Note: we can probably return user's current role/permission as well
                // if there's no performance issue on fetching

                $user->profile_image = $user->getAvatar();
                $user->name = __('text.useremail', ['user' => $user->name, 'email' => $user->email]);

                return $user;
            });
        // Remove super admins when auth user was a product admin
        $users = $users->filter(function (User $user) {
            $isAuthSuperAdmin = auth()->user()->hasRole(config('const.ROLE_SUPER_ADMIN'));

            if (! $isAuthSuperAdmin) {
                return ! $user->hasRole(config('const.ROLE_SUPER_ADMIN'));
            }

            // Prevent user to search itself
            if (auth()->user()->id == $user->id) {
                return false;
            }

            return true;
        });
        // Reset collection keys
        $users = collect($users->values());

        return $users;
    }
}
