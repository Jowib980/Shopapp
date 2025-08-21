<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $fillable = ['name', 'type', 'buy_quantity', 'free_quantity', 'discount_percent'];

    protected $casts = [
        'parameters' => 'array'
    ];

    public function products()
    {
        return $this->belongsToMany(Products::class, 'product_offers', 'offer_id', 'product_id');
    }
}
