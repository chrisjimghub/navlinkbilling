<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Support\Facades\Route;

trait TestRoute {
    protected function setupTestRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/test', [
            'as'        => $routeName.'.test',
            'uses'      => $controller.'@test',
            'operation' => 'test',
        ]);

    }

    public function test($id)
    {
        dd('test run');
    }
}