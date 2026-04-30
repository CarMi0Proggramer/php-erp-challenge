<?php

namespace App\Observers;

use App\Jobs\DeleteProductImagesJob;
use App\Models\Product;

class ProductObserver
{
    public function forceDeleting(Product $product): void
    {
        $imagesPaths = $product->images()
            ->pluck('path')
            ->toArray();

        $descriptionImagesPaths = $product
            ->descriptionImages()
            ->pluck('path')
            ->toArray();

        $paths = array_merge($imagesPaths, $descriptionImagesPaths);

        DeleteProductImagesJob::dispatch($paths);
    }
}
