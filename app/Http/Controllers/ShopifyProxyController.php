<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\ProductOption;
use App\Models\ProductImage;
// use App\Models\ProductOffers;

class ShopifyProxyController extends Controller
{


   public function getProducts()
{
    $shop = "jowib-emporium.myshopify.com";
    $accessToken = config('services.shopify.access_token');

    // Fetch products from Shopify
    $response = Http::withHeaders([
        "X-Shopify-Access-Token" => $accessToken,
        "Content-Type" => "application/json",
    ])->get("https://$shop/admin/api/2025-07/products.json");

    $productData = $response->json();
    // dd($productData); // ✅ Remove once verified

    $savedProducts = [];

    // Don't overwrite $productData, use a different name
    foreach ($productData['products'] as $shopifyProduct) {

        $product = Products::updateOrCreate(
            ['shopify_id' => $shopifyProduct['id']], // ✅ Correct Shopify product ID
            
            [
                'title' => $shopifyProduct['title'],
                'body_html' => $shopifyProduct['body_html'],
                'vendor' => $shopifyProduct['vendor'],
                'product_type' => $shopifyProduct['product_type'],
                'handle' => $shopifyProduct['handle'],
                'template_suffix' => $shopifyProduct['template_suffix'],
                'published_scope' => $shopifyProduct['published_scope'],
                'status' => $shopifyProduct['status']
            ]
        );

        // Save/update variants
        foreach ($shopifyProduct['variants'] as $variantData) {
            $product->variants()->updateOrCreate(
                ['shopify_id' => $variantData['id']],
                [
                    'product_id' => $product->id,
                    'title' => $variantData['title'],
                    'price' => $variantData['price'],
                    'inventory_policy' => $variantData['inventory_policy'],
                    'position' => $variantData['position'],
                    'option1' => $variantData['option1'],
                    'option2' => $variantData['option2'],
                    'option3' => $variantData['option3'],
                    'sku' => $variantData['sku'],
                    'inventory_quantity' => $variantData['inventory_quantity'],
                    'taxable' => $variantData['taxable'],
                ]
            );
        }

        // Save/update options
        foreach ($shopifyProduct['options'] as $optionData) {
            $product->options()->updateOrCreate(
                ['product_id' => $product->id, 'name' => $optionData['name']],
                [
                    'position' => $optionData['position'],
                    'values' => json_encode($optionData['values']),
                ]
            );
        }

        // Save/update images
        foreach ($shopifyProduct['images'] ?? [] as $imageData) {
            $product->images()->updateOrCreate(
                ['shopify_id' => $imageData['id']],
                [
                    'product_id' => $product->id,
                    'src' => $imageData['src'],
                    'position' => $imageData['position'],
                    'width' => $imageData['width'],
                    'height' => $imageData['height'],
                    'alt' => $imageData['alt'],
                ]
            );
        }

        $savedProducts[] = $product;
    }

    return response()->json($savedProducts);
}


}
