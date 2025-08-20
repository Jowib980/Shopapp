<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offers;
use App\Models\ProductOffers;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offers::all();
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:free,discount',
            'parameters' => 'required',
        ]);

        Offers::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'parameters' => $data['parameters'],
        ]);

        return redirect()->route('offers.index')->with('success', 'Offer created');
    }

    public function editProductOffer($productId)
    {
        $offers = Offers::all();
        $productOffer = ProductOffers::where('shopify_product_id', $productId)->first();

        return view('admin.offers.edit_product_offer', compact('offers', 'productId', 'productOffer'));
    }

    public function updateProductOffer(Request $request, $productId)
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id'
        ]);

        ProductOffers::updateOrCreate(
            ['shopify_product_id' => $productId],
            ['offer_id' => $request->offer_id]
        );

        return redirect()->back()->with('success', 'Offer assigned successfully!');
    }
}

