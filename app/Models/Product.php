<?php

namespace App\Models;

use App\Enums\ProductSize;
use App\Observers\ProductObserver;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ProductObserver::class)]
#[Fillable([
    'name',
    'sku',
    'stock',
    'sizes',
    'price',
    'description',
])]
class Product extends Model
{
    use Filterable, HasFactory, HasUuids, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sizes' => AsEnumCollection::of(ProductSize::class),
            'price' => 'float',
        ];
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true);
    }

    public function descriptionImages()
    {
        return $this->hasMany(ProductDescriptionImage::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
