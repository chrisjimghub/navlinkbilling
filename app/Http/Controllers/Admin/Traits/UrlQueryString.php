<?php

namespace App\Http\Controllers\Admin\Traits;

trait UrlQueryString
{
    public function googleMapLink($coordinates)
    {
        return url('https://www.google.com/maps?q='.$coordinates);
    }
}
