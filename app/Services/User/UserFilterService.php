<?php

namespace App\Services\User;

use App\DataTransferObject\UserFilterDto;
use App\Filters\Common\SearchField;
use App\Filters\User\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class UserFilterService
{
    /**
     * Filter users based on various criteria.
     *
     * @param  UserFilterDto  $filter  The filters to be applied
     * @return \Illuminate\Database\Eloquent\Builder The filtered query builder.
     */
    public function filter(UserFilterDto $filter): Builder
    {
        $users = app(Pipeline::class)
            ->send([
                'query' => User::query(),
                'role' => $filter->role,
                'search_field' => [
                    'field' => $filter->searchFields,
                    'value' => $filter->searchValue,
                ],
            ])
            ->through([
                Role::class, // Apply role filter
                SearchField::class, // Search filter
                // Add more filters as needed
            ])
            ->thenReturn();

        return $users['query'];
    }
}
