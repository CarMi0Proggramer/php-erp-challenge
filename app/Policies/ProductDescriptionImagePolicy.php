<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\ProductDescriptionImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductDescriptionImagePolicy
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
            : Response::deny('Você não tem permissão para guardar imagens de produtos.');
    }

    public function delete(
        User $user,
        Product $product,
        ProductDescriptionImage $image
    ) {
        if (!$user->isAdmin() && !$user->isOperator()) {
            return Response::deny('Você não tem permissão para deletar imagens de produtos.');
        };

        if ($product->id !== $image->product_id) {
            return Response::deny('A imagem sendo deletada não corresponde a esse produto.');
        }

        return Response::allow();
    }
}
