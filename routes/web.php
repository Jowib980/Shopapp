<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyProxyController;
use App\Http\Controllers\ShopifyAuthController;
use App\Http\Controllers\Admin\OfferController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/shopify/install', [ShopifyAuthController::class, 'install']);
Route::get('/shopify/callback', [ShopifyAuthController::class, 'callback']);

Route::middleware(['shopify.cors'])
    ->group(function () {
        Route::get('/products', [ShopifyProxyController::class, 'getProducts']);
    });



Route::prefix('admin')->group(function () {
    Route::resource('offers', OfferController::class);
    Route::get('products/{id}/offers', [OfferController::class, 'editProductOffer']);
    Route::post('products/{id}/offers', [OfferController::class, 'updateProductOffer']);
});
