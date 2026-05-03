<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    /**
     * Handles product stock adjustment movement
     */
    public function adjust(
        Product $product,
        User $user,
        int $balance,
        ?string $reason = null
    ) {
        DB::transaction(function () use ($product, $user, $balance, $reason) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'reason' => $reason,
                'type' => StockMovementType::ADJUSTMENT,
                'stock_before' => $product->stock,
                'stock_balance' => $balance,
            ]);

            $product->update(['stock' => $balance]);
        });
    }
}
