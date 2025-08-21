<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offers;
use App\Models\ProductOffers;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    public function offeredProducts(Request $request) {
        $data = ProductOffers::with(['product', 'offer'])->get();

        return $data;
    }
    
}

