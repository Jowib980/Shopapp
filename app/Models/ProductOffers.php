<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOffers extends Model
{
    protected $fillable = ['shopify_product_id', 'offer_id'];

    public function offer()
    {
        return $this->belongsTo(Offers::class);
    }
}

