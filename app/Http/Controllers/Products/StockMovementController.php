<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Stock\StockMovementStoreRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockMovementService;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Inertia\Inertia;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService
    ) {}

    public function index(Product $product)
    {
        $stockPaginator = $product->stockMovements()
            ->with('user:id,name')
            ->latest()
            ->paginate(5);

        return Inertia::render('products/ProductStock', [
            'product' => $product->load('primaryImage'),
            'stockMovements' => $stockPaginator->items(),
            'pagination' => [
                'perPage' => $stockPaginator->perPage(),
                'total' => $stockPaginator->total(),
                'currentPage' => $stockPaginator->currentPage(),
                'lastPage' => $stockPaginator->lastPage(),
            ],
        ]);
    }

    #[Authorize('create', StockMovement::class)]
    public function store(StockMovementStoreRequest $request, Product $product)
    {
        $user = $request->user();
        $balance = $request->input('balance');
        $reason = $request->input('reason');

        $this->stockMovementService->adjust(
            $product,
            $user,
            $balance,
            $reason
        );

        return to_route('products.stock', ['product' => $product]);
    }
}
