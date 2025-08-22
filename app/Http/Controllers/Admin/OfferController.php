<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offers;
use App\Models\ProductOffers;
use App\Models\Products;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    public function offeredProducts(Request $request) {
        $data = ProductOffers::with(['product', 'offer'])->get();

        return $data;
    }

    public function updateCart(Request $request) 
    {
        
        $productVariantId = (string) $request->product_variant_id;
        $quantity = $request->quantity;

        $productVariant = ProductVariant::where('shopify_id', $productVariantId)->first();
        if (!$productVariant) {
            return response()->json(['error' => 'Product variant not found'], 404);
        }

        $product_original_price = $productVariant->price;

        // Get discount/offer for this product
        $productOffer = ProductOffers::with('offer')
            ->where('product_id', $productVariant->product_id)
            ->first();

        $freeQty = 0;
        $discountPercent = 0;
        $totalQty = $quantity;

        if ($productOffer && $productOffer->offer) {
            
            $offer = $productOffer->offer;
            $discountPercent = $offer->discount_percent;

            if($offer->buy_quantity && $offer->free_quantity) {
                $sets = floor($quantity / $offer->buy_quantity);
                $freeQty = $sets * $offer->free_quantity;
            }

            $totalQty = $quantity + $freeQty; 
        }


        $discountedPrice = $product_original_price;
        if($discountPercent > 0) {
            $discountedPrice = $product_original_price - ($product_original_price * $discountPercent / 100);
        }

        $totalPrice = $discountedPrice * $quantity;

        return response()->json([
            'product_variant_id' => $productVariantId,
            'quantity' => $quantity,
            'free_qty' => $freeQty,
            'total_qty' => $totalQty,
            'original_price' => $product_original_price,
            'discount_percent' => $discountPercent,
            'discounted_price' => round($discountedPrice, 2),
            'total_price' => round($totalPrice, 2),
            'offer_name' => $productOffer->offer->name ?? null
        ]);
    }

    
}

