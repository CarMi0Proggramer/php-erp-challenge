<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductStoreRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $paginator = Product::filter($request->query())->paginate(15);

        return Inertia::render('products/Products', [
            'products' => $paginator->items(),
            'pagination' => [
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
            ],
            'filters' => $request->only([
                'searchTerm',
                'priceFrom',
                'priceTo',
                'stockFrom',
                'stockTo',
                'sizes',
            ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('products/CreateProduct');
    }

    #[Authorize('create', Product::class)]
    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();
        $sku = str($validated['name'])
            ->upper()
            ->ascii()
            ->substr(0, 3)
            ->append('-', str()->random(6));

        $product = Product::create([...$validated, 'sku' => $sku]);

        return to_route('products.edit', ['product' => $product]);
    }

    public function edit(Product $product)
    {
        return Inertia::render('products/EditProduct', [
            'product' => $product,
        ]);
    }

    #[Authorize('update', Product::class)]
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());

        return back()->with('message', 'Produto atualizado com sucesso');
    }

    #[Authorize('delete', Product::class)]
    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('message', 'Produto deletado com sucesso');
    }
}
