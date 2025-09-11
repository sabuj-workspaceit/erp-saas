<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use Illuminate\Support\Str;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $host = $request->getHost();
        $base = config('app-domain.base'); // e.g. myapp.test


        if ($host === $base || Str::endsWith($host, 'localhost')) {
            // Main domain (marketing, registration, system admin)
            app()->forgetInstance('tenant');
            return $next($request);
        }


        $sub = Str::before($host, '.' . $base);
        $tenant = Tenant::where('subdomain', $sub)->first();
        abort_if(!$tenant, 404, 'Tenant not found');


        app()->instance('tenant', $tenant);
        return $next($request);
    }
}
