<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVideo extends Model
{
    protected $fillable = ['product_id', 'video_url', 'caption'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
