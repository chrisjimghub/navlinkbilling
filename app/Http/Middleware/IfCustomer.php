<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IfCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // NOTE:: i didnt put an if statement to check if authenticated is customer, because i will only use this middleware only in the customer portal routes.
        config(['backpack.base.route_prefix' => 'customer']);

        return $next($request);
    }
}
