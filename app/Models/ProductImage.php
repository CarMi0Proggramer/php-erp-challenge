<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'url',
    'product_id',
    'is_primary',
])]
class ProductImage extends Model
{
    use HasFactory, HasUuids;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
