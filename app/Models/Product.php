<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'regular_price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'stock_status',
        'status',
        'featured',
        'new_arrival',
        'best_seller',
        'ingredients',
        'how_to_use',
        'skin_type',
        'skin_concerns',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',

            'stock_quantity' => 'integer',

            'featured' => 'boolean',
            'new_arrival' => 'boolean',
            'best_seller' => 'boolean',
        ];
    }

    protected $appends = ['current_price'];

    public function getCurrentPriceAttribute(): float
    {
        if (
            $this->sale_price !== null &&
            $this->sale_price < $this->regular_price
        ) {
            return (float) $this->sale_price;
        }

        return (float) $this->regular_price;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }


    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('is_approved', true);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
