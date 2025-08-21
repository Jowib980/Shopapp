<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopify_id',
        'title',
        'body_html',
        'vendor',
        'product_type',
        'handle',
        'template_suffix',
        'published_scope',
        'status',
    ];

    /**
     * Product has many variants
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    /**
     * Product has many options
     */
    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id');
    }

    /**
     * Product has many images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'product_offers', 'product_id', 'offer_id');
    }
}
