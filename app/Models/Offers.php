<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $fillable = ['name', 'type', 'parameters'];

    protected $casts = [
        'parameters' => 'array'
    ];

    public function products()
    {
        return $this->hasMany(ProductOffers::class);
    }
}
