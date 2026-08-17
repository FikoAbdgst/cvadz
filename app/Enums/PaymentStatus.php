<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case BelumBayar = 'belum';
    case DP = 'dp';
    case Lunas = 'lunas';

    public function label(): string
    {
        return match ($this) {
            self::BelumBayar => 'Belum Bayar',
            self::DP => 'DP',
            self::Lunas => 'Lunas',
        };
    }
}
