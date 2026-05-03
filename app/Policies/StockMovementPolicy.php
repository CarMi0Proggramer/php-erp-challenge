<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockMovementPolicy
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
            : Response::deny('Você não tem permissão para fazer movimentos no estoque de produtos.');
    }
}
