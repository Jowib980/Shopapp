<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyProxyController;
use App\Http\Controllers\ShopifyAuthController;
use App\Http\Controllers\Admin\OfferController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/shopify/install', [ShopifyAuthController::class, 'install']);
Route::get('/shopify/callback', [ShopifyAuthController::class, 'callback'])->name('shopify.callback');

Route::prefix('shopify/proxy')
    ->group(function () {
        Route::get('/products', [ShopifyProxyController::class, 'getProducts']);
        Route::get('/offer-products', [OfferController::class, 'offeredProducts']);
        Route::get('/update-cart', [OfferController::class, 'updateCart']);
    });
