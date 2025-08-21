<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOffers extends Model
{
    
    protected $table = 'product_offers';
    protected $fillable = ['product_id', 'offer_id'];

    public function product() {
        return $this->belongsTo(Products::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offers::class);
    }
}