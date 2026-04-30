<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductImagePolicy
{
    public function create(User $user)
    {
        return $this->hasPermission($user)
            ? Response::allow()
            : Response::deny('Você não tem permissão para guardar imagens de produtos.');
    }

    public function delete(
        User $user,
        ProductImage $image,
        Product $product
    ) {
        if (! $this->hasPermission($user)) {
            return Response::deny('Você não tem permissão para deletar imagens de produtos.');
        }

        if (! $this->belongsToProduct($image, $product)) {
            return Response::deny('A imagem sendo deletada não corresponde a esse produto.');
        }

        return Response::allow();
    }

    public function markAsPrimary(
        User $user,
        ProductImage $image,
        Product $product
    ) {

        if (! $this->hasPermission($user)) {
            return Response::deny('Você não tem permissão para marcar imagens de produtos como principais.');
        }

        if (! $this->belongsToProduct($image, $product)) {
            return Response::deny('A imagem sendo marcada como principal não corresponde a esse produto.');
        }

        return Response::allow();
    }

    private function hasPermission(User $user): bool
    {
        return $user->isAdmin() || $user->isOperator();
    }

    private function belongsToProduct(ProductImage $image, Product $product): bool
    {
        return $image->product_id === $product->id;
    }
}
