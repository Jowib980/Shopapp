<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ShopifyCors
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', '*')
                 ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                 ->header('Access-Control-Allow-Headers', 'Content-Type, X-Shopify-Access-Token');

        return $response;
    }
}

