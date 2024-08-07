<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ThemeSwitchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ThemeCrudController extends CrudController
{
    public function setup()
    {
        CRUD::setRoute(config('backpack.base.route_prefix') . '/theme');
    }

    protected function setupThemeRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/switchLayout', [
            'as'        => $routeName.'.switchLayout',
            'uses'      => $controller.'@switchLayout',
            'operation' => 'switchLayout',
        ]);
    }    

    public function switchLayout(Request $request)
    {
        $theme = 'backpack.theme-'.$request->get('theme', 'tabler').'::';
    
        $data = [
            'backpack.ui.view_namespace' => $theme,
        ];

        if ($theme === 'backpack.theme-tabler::') {
            // Session::put('backpack.theme-tabler.layout', $request->get('layout', 'vertical'));
            $data['backpack.theme-tabler.layout'] = $request->get('layout', 'vertical'); 
        }

        $user = Auth::user();
        $user->theme = $data;
        $user->save();

        return redirect()->back();
    }
}
