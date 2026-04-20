<?php

namespace App\Concerns;

use App\Enums\Role;
use Illuminate\Validation\Rule;

trait RoleValidationRules
{
    /**
     * Get the validation rules for the role field.
     *
     * @return array<string>
     */
    public function roleRules(): array
    {
        return ['required', 'string', Rule::enum(Role::class)];
    }
}
