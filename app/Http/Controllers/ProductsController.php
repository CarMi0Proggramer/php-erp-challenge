<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
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
}
