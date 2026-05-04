<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NetworkRestrictionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedNetworks = env('ALLOWED_NETWORKS');

        // If no networks are defined, we allow all access (safety default)
        if (empty($allowedNetworks)) {
            return $next($request);
        }

        $allowedIps = array_map('trim', explode(',', $allowedNetworks));

        // Always allow localhost for development
        $allowedIps[] = '127.0.0.1';
        $allowedIps[] = '::1';

        $clientIp = $request->ip();

        // Simple IP check. For production, you might want to support CIDR ranges.
        if (! in_array($clientIp, $allowedIps)) {
            abort(403, 'Access restricted to company network or VPN. Your IP: '.$clientIp);
        }

        return $next($request);
    }
}
