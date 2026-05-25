<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Store tenant globally in request
        $tenantId = auth()->user()->tenant_id;

        if (!$tenantId) {
            abort(403, 'Tenant not found.');
        }

        // Share globally for queries/views
        app()->instance('tenant_id', $tenantId);

        return $next($request);
    }
}