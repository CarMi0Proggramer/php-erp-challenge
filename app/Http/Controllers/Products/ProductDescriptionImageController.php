<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Images\ProductDescriptionImageStoreRequest;
use App\Models\Product;
use App\Models\ProductDescriptionImage;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Storage;

class ProductDescriptionImageController extends Controller
{
    #[Authorize('create', ProductDescriptionImage::class)]
    public function store(ProductDescriptionImageStoreRequest $request, Product $product)
    {
        $path = $request->file('image')
            ->store('products/description-images', 'public');

        $image = ProductDescriptionImage::create([
            'product_id' => $product->id,
            'path' => $path,
        ]);

        return ['id' => $image->id, 'url' => Storage::url($path)];
    }

    #[Authorize('delete', ['product', 'description_image'])]
    public function destroy(Product $product, ProductDescriptionImage $description_image)
    {
        Storage::delete($description_image->path);
        $description_image->delete();

        return ['message' => 'Imagem deletada com sucesso'];
    }
}
