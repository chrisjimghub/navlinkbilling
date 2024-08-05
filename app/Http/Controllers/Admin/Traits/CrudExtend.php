<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\Admin\Traits\AccountCrud;
use App\Http\Controllers\Admin\Traits\CustomerCrud;
use App\Http\Controllers\Admin\Traits\UtilityHelper;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;
use App\Http\Controllers\Admin\Traits\UrlQueryString;
use App\Http\Controllers\Admin\Traits\ValidateUniqueRule;
use App\Http\Controllers\Admin\Traits\PlannedApplicationCrud;
use Winex01\BackpackPermissionManager\Http\Controllers\Traits\UserPermissions;

trait CrudExtend
{
    use UserPermissions;
    use UtilityHelper;
    use CurrencyFormat;
    use UrlQueryString;
    use ValidateUniqueRule;

    use CustomerCrud;
    use PlannedApplicationCrud;
    use AccountCrud;
}
