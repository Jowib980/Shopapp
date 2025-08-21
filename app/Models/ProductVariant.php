<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopify_id',
        'product_id',
        'title',
        'price',
        'inventory_policy',
        'position',
        'option1',
        'option2',
        'option3',
        'sku',
        'inventory_quantity',
        'taxable',
    ];

    protected $casts = [
        'shopify_id' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
