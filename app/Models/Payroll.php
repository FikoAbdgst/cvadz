<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['worker_id', 'period', 'total_days', 'salary_amount', 'bonus', 'lemburan', 'uang_luar_kota', 'kasbon', 'status', 'approved_by', 'approved_at'])]
class Payroll extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'salary_amount' => 'decimal:2',
            'bonus' => 'decimal:2',
            'lemburan' => 'decimal:2',
            'uang_luar_kota' => 'decimal:2',
            'kasbon' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    protected function netSalary(): Attribute
    {
        return Attribute::get(function () {
            return (float) $this->salary_amount
                + (float) $this->bonus
                + (float) $this->lemburan
                + (float) $this->uang_luar_kota
                - (float) $this->kasbon;
        });
    }

    /**
     * Get the worker of this payroll.
     *
     * @return BelongsTo<Worker, $this>
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * Get the user who approved this payroll.
     *
     * @return BelongsTo<User, $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
