<?php

namespace App\Exports;

use App\Models\Billing;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class BillingExport implements FromQuery
{
    use Exportable;

    public function query()
    {
        return Billing::query();
    }

}
