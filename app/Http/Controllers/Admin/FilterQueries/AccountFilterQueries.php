<?php  

namespace App\Http\Controllers\Admin\FilterQueries;

trait AccountFilterQueries
{
    public function accountFilterQueries($query)
    {
        $status = request()->input('status');
        $sub = request()->input('subscription');

        if ($status) {
            $query->withStatus($status);
        }

        if ($sub) {
            $query->withSubscription($sub);
        }

        return $query;
    }
}
