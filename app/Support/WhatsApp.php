<?php

namespace App\Support;

class WhatsApp
{
    public static function link(string $message): string
    {
        return 'https://wa.me/'.config('services.whatsapp.number').'?text='.rawurlencode($message);
    }
}
