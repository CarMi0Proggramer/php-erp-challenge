<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function before(User $user)
    {
        return $user->isAdmin()
            ? Response::allow()
            : null;
    }

    public function create(User $user)
    {
        return $user->isOperator()
            ? Response::allow()
            : Response::deny('Você não tem permissão para criar produtos.');
    }

    public function update(User $user)
    {
        return $user->isOperator()
            ? Response::allow()
            : Response::deny('Você não tem permissão para atualizar produtos.');
    }

    public function delete(User $user)
    {
        return $user->isOperator()
            ? Response::allow()
            : Response::deny('Você não tem permissão para deletar produtos.');
    }
}
