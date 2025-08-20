<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyShopifyProxySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        // Shopify App Proxy sends ?signature=... plus other query params
        $signature = $request->query('signature') ?? $request->query('hmac') ?? null;
        if (!$signature) {
            abort(401, 'Missing signature');
        }

        $secret = config('services.shopify.api_secret');

        // Use the RAW query string to avoid encoding differences
        $raw = $request->getQueryString() ?? '';
        // Remove the signature param itself from the raw string
        $base = preg_replace('/(^|&)signature=[^&]*/', '', $raw);
        $base = ltrim($base, '&');

        $computed = hash_hmac('sha256', $base, $secret);

        if (!hash_equals($signature, $computed)) {
            abort(401, 'Invalid signature');
        }

        return $next($request);
    }

}
