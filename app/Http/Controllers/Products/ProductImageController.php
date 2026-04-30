<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Images\ProductImageStoreRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    #[Authorize('create', ProductImage::class)]
    public function store(ProductImageStoreRequest $request, Product $product)
    {
        $path = $request->file('image')->store('products/images', 'public');
        $url = Storage::url($path);

        $image = ProductImage::create([
            'product_id' => $product->id,
            'url' => $url,
            'path' => $path,
        ]);

        return $image;
    }

    #[Authorize('markAsPrimary', ['image', 'product'])]
    public function markAsPrimary(Product $product, ProductImage $image)
    {
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return $image;
    }

    #[Authorize('delete', ['image', 'product'])]
    public function destroy(Product $product, ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return ['message' => 'Imagem deletada com sucesso'];
    }
}
