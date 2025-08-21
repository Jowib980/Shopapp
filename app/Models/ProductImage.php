<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopify_id',
        'product_id',
        'src',
        'position',
        'width',
        'height',
        'alt',
    ];

    protected $casts = [
        'shopify_id' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
