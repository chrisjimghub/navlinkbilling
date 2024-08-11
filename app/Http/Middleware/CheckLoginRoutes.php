<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request URL is /login
        if ($request->is('login')) {
            Config::set('backpack.theme-tabler.auth_layout', 'cover');   
        }
        
        // Check if the request URL is /admin/login
        else if ($request->is('admin/login')) {
            Config::set('backpack.theme-tabler.auth_layout', 'default');   
        }

        return $next($request);
    }
}
