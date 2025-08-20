<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopifyAuthController extends Controller
{

    public function install(Request $request)
	{
	    $shop = $request->get('shop'); // must be jowib-emporium.myshopify.com
	    $apiKey = config('services.shopify.api_key');
	    $scopes = "read_products,write_products";
	    $redirectUri = route('shopify.callback'); // https://emporium.cardiacambulance.com/shopify/callback

	    if (!$shop) {
	        return response("Missing shop parameter", 400);
	    }

	    $installUrl = "https://{$shop}/admin/oauth/authorize?" . http_build_query([
	        'client_id'    => $apiKey,
	        'scope'        => $scopes,
	        'redirect_uri' => $redirectUri,
	    ]);

	    return redirect()->away($installUrl);
	}

    public function callback(Request $request)
    {
        $hmac = $request->query('hmac');
        $shop = $request->query('shop');
        $code = $request->query('code');

        if (!$hmac || !$shop || !$code) {
            abort(400, 'Invalid callback params');
        }

        // Exchange code for permanent token
        $response = Http::asForm()->post("https://{$shop}/admin/oauth/access_token", [
            'client_id' => config('services.shopify.api_key'),
            'client_secret' => config('services.shopify.api_secret'),
            'code' => $code,
        ]);

        $data = $response->json();
        $accessToken = $data['access_token'] ?? null;

        if (!$accessToken) {
            abort(500, 'Failed to get access token: ' . json_encode($data));
        }

        // TODO: Save $accessToken in DB for this shop
        return response()->json([
            'ok' => true,
            'shop' => $shop,
            'access_token' => $accessToken,
        ]);
    }

}
