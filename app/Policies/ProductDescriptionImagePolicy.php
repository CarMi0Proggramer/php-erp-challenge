<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\ProductDescriptionImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductDescriptionImagePolicy
{
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isOperator()
            ? Response::allow()
            : Response::deny('Você não tem permissão para guardar imagens de produtos.');
    }

    public function delete(
        User $user,
        ProductDescriptionImage $description_image,
        Product $product
    ) {
        if (! $user->isAdmin() && ! $user->isOperator()) {
            return Response::deny('Você não tem permissão para deletar imagens de produtos.');
        }

        if ($product->id !== $description_image->product_id) {
            return Response::deny('A imagem sendo deletada não corresponde a esse produto.');
        }

        return Response::allow();
    }
}
