<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id', 'service_id', 'quantity', 'notes', 'status', 'admin_user_id', 'total', 'warranty_end_date', 'payment_status', 'payment_amount', 'payment_type', 'payment_date', 'payment_proof'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'status' => OrderStatus::class,
            'total' => 'decimal:2',
            'warranty_end_date' => 'date',
            'payment_status' => PaymentStatus::class,
            'payment_amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function itemLabel(): string
    {
        return $this->product?->name ?? $this->service?->name ?? '—';
    }

    public function hasWarranty(): bool
    {
        return $this->warranty_end_date !== null;
    }

    public function isWarrantyActive(): bool
    {
        return $this->hasWarranty() && $this->warranty_end_date->isPast() === false;
    }

    public function warrantyStatus(): string
    {
        if (! $this->hasWarranty()) {
            return 'tanpa_garansi';
        }

        return $this->isWarrantyActive() ? 'aktif' : 'kedaluwarsa';
    }

    /**
     * The admin user who handled this order.
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function hasPayment(): bool
    {
        return $this->payment_status !== null && $this->payment_status !== PaymentStatus::BelumBayar;
    }

    public function proofUrl(): ?string
    {
        return $this->payment_proof ? Storage::disk('public')->url($this->payment_proof) : null;
    }

    public function paymentStatusLabel(): string
    {
        return $this->payment_status?->label() ?? 'Belum Bayar';
    }
}
