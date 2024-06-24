<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }

    /**
     * Get the validation rules used to validate passwords.
     */
    protected function currentPasswordRules(User $user): array
    {
        if ($user->isSocialiteHasNoPassword()) {
            return [];
        }

        return ['required', 'string'];
    }
}
