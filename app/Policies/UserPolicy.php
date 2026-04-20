<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    /**
     * Determines whether the user can create users.
     */
    public function create(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Você não tem permissão para criar usuários.');
    }

    /**
     * Determines whether the user can update users.
     */
    public function update(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Você não tem permissão para atualizar usuários.');
    }

    /**
     * Determines whether the user can delete users.
     */
    public function destroy(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Você não tem permissão para deletar usuários.');
    }
}
