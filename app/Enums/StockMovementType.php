<?php

namespace App\Enums;

enum StockMovementType: string
{
    case ADJUSTMENT = 'adjustment';
    case SALE = 'sale';
    case PURCHASE = 'purchase';
}
