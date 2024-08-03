<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Theme
{
    public function handle($request, Closure $next): mixed
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the theme settings from the authenticated user
            $themeSettings = Auth::user()->theme;

            // Check if theme settings are not empty and contain the necessary keys
            if (!empty($themeSettings)) {
                if (isset($themeSettings['backpack.ui.view_namespace'])) {
                    Config::set('backpack.ui.view_namespace', $themeSettings['backpack.ui.view_namespace']);
                }

                // Check if the theme is Tabler and set the layout if the key exists
                if (isset($themeSettings['backpack.ui.view_namespace']) && $themeSettings['backpack.ui.view_namespace'] === 'backpack.theme-tabler::') {
                    if (isset($themeSettings['backpack.theme-tabler.layout'])) {
                        Config::set('backpack.theme-tabler.layout', $themeSettings['backpack.theme-tabler.layout']);
                    } else {
                        Config::set('backpack.theme-tabler.layout', config('backpack.theme-tabler.layout'));
                    }
                }
            }else {
                // if user theme is empty then show coreui2 as default theme for admin ang tabler horizontal for customer 
                if (Auth::user()->belongsToCustomer()) {
                    // default theme for customer if theme is empty
                    Config::set('backpack.ui.view_namespace', 'backpack.theme-tabler::');
                    Config::set('backpack.theme-tabler.layout', 'horizontal_overlap');
                }else {
                    // default theme for admin
                    Config::set('backpack.ui.view_namespace', 'backpack.theme-coreuiv2::');
                }

            }
        }

        
        return $next($request);
    }
}
