<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'position', 'phone', 'salary'])]
class Worker extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2',
        ];
    }

    /**
     * Get the attendances belonging to this worker.
     *
     * @return HasMany<Attendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the payrolls belonging to this worker.
     *
     * @return HasMany<Payroll, $this>
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
