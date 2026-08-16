<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Diproses = 'diproses';
    case Selesai = 'selesai';
    case Batal = 'batal';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Diproses => 'Diproses',
            self::Selesai => 'Selesai',
            self::Batal => 'Batal',
        };
    }
}
