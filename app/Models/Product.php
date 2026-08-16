<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'slug', 'description', 'price', 'is_featured', 'warranty_months', 'stock'])]
class Product extends Model
{
    public const LOW_STOCK_THRESHOLD = 5;

    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'warranty_months' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->stock <= self::LOW_STOCK_THRESHOLD;
    }

    public function stockStatus(): string
    {
        if ($this->stock === 0) {
            return 'habis';
        }

        return $this->isLowStock() ? 'kritis' : 'aman';
    }

    public function warrantyLabel(): string
    {
        if (! $this->warranty_months) {
            return 'Tanpa garansi';
        }

        return 'Garansi '.$this->warranty_months.' bulan';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideo::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function primaryImage(): ?ProductImage
    {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
    }

    public function whatsappMessage(): string
    {
        return 'Halo, saya tertarik dengan produk '.$this->name.', mohon info lebih lanjut.';
    }
}
