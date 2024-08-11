<?php

namespace App\Http\Controllers\Customer\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Backpack\CRUD\app\Library\Auth\AuthenticatesUsers;
use Backpack\CRUD\app\Http\Controllers\Auth\LoginController as BackpackLoginController;

class LoginController extends BackpackLoginController
{
    protected ?string $loginPath = '/login';
    protected ?string $redirectTo = null;
    protected ?string $redirectAfterLogout = '/login';
}
