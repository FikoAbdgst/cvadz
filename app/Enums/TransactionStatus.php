<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Lunas = 'lunas';
    case BelumLunas = 'belum_lunas';

    public function label(): string
    {
        return match ($this) {
            self::Lunas => 'Lunas',
            self::BelumLunas => 'Belum Lunas',
        };
    }
}
