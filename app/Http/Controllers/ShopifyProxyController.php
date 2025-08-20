<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ProductOffers;

class ShopifyProxyController extends Controller
{


    public function getProducts()
    {
        $shop = "jowib-emporium.myshopify.com";
        $accessToken = config('services.shopify.access_token'); // your Admin API token

        // Fetch products from Shopify
        $response = Http::withHeaders([
            "X-Shopify-Access-Token" => $accessToken,
            "Content-Type" => "application/json",
        ])->get("https://$shop/admin/api/2025-07/products.json");

        $products = $response->json();

        // Attach offer info from Laravel DB
        foreach ($products['products'] as &$product) {
            $productOffer = ProductOffers::where('shopify_product_id', $product['id'])->first();
            if ($productOffer) {
                $product['offer'] = [
                    'id' => $productOffer->offer->id,
                    'type' => $productOffer->offer->type,
                    'parameters' => $productOffer->offer->parameters,
                    'name' => $productOffer->offer->name
                ];
            } else {
                $product['offer'] = null;
            }
        }

        return response()->json($products);
    }

}
