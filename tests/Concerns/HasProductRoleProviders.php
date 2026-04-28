<?php

namespace Tests\Concerns;

use App\Enums\Role;

trait HasProductRoleProviders
{
    public static function allowedRolesProvider()
    {
        return [
            [Role::ADMIN],
            [Role::OPERATOR],
        ];
    }

    public static function forbiddenRolesProvider()
    {
        $allowedRoles = [Role::ADMIN, Role::OPERATOR];
        $forbiddenRoles = array_filter(
            Role::cases(),
            fn ($role) => ! in_array($role, $allowedRoles)
        );

        return array_map(fn ($role) => [$role], $forbiddenRoles);
    }
}
