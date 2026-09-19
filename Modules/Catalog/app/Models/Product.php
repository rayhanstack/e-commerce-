<?php

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Models\Stock;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'type',
        'sku',
        'barcode',
        'buying_price',
        'selling_price',
        'special_price',
        'stock_alert_quantity',
        'unit',
        'is_active',
        'is_featured',
        'track_inventory',
        'has_serials',
        'has_batches',
        'short_description',
        'description',
        'specifications',
        'images',
        'featured_image',
    ];

    protected $casts = [
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'stock_alert_quantity' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'track_inventory' => 'boolean',
        'has_serials' => 'boolean',
        'has_batches' => 'boolean',
        'specifications' => 'array',
        'images' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function getActivePriceAttribute(): float
    {
        return (float) ($this->special_price ?: $this->selling_price);
    }
}
