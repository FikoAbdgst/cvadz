<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Orders handled by this user as admin.
     *
     * @return HasMany<Order, $this>
     */
    public function adminOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'admin_user_id');
    }

    /**
     * Transactions recorded by this user as staff.
     *
     * @return HasMany<Transaction, $this>
     */
    public function staffTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'staff_user_id');
    }

    /**
     * Cashbook entries recorded by this user.
     *
     * @return HasMany<Cashbook, $this>
     */
    public function cashbooks(): HasMany
    {
        return $this->hasMany(Cashbook::class, 'user_id');
    }
}
