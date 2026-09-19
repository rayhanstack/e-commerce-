<?php

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Models\Stock;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'buying_price',
        'selling_price',
        'special_price',
        'attribute_values',
        'image',
        'is_active',
    ];

    protected $casts = [
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'attribute_values' => 'array',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function getActivePriceAttribute(): float
    {
        if ($this->special_price) {
            return (float) $this->special_price;
        }
        if ($this->selling_price > 0) {
            return (float) $this->selling_price;
        }

        return $this->product ? $this->product->active_price : 0.00;
    }
}
