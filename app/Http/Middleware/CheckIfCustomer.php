<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( auth()->check() && auth()->user()->isCustomer() ) {
            $origRoutePrefix = config('backpack.base.route_prefix');
            
            config(['backpack.base.route_prefix' => 'customer']);

            if ($request->is($origRoutePrefix.'/dashboard')) {
                return redirect('customer/dashboard');
            }
        }

        return $next($request);
    }

}
