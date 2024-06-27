<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\Admin\Traits\CustomerCrud;
use App\Http\Controllers\Admin\Traits\UtilityHelper;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use App\Http\Controllers\Admin\Traits\UrlQueryString;
use App\Http\Controllers\Admin\Traits\UserPermissions;
use App\Http\Controllers\Admin\Traits\ValidateUniqueRule;

trait CrudExtend
{
    use UserPermissions;
    use UtilityHelper;
    use CurrencyFormat;
    use UrlQueryString;
    use ValidateUniqueRule;

    use CustomerCrud;
}
