<?php

namespace App\Exports;

use App\Models\Account;
use Illuminate\Support\Carbon;


class InstallAccountExport extends CutOffAccountExport
{
    protected $title = 'Install Accounts';

    public function query()
    {
        return Account::
                notInstalled()                
                ->orderBy('created_at', 'asc');
    }

    public function headings(): array
    {
        return [
            __('app.row_num'),
            __('app.customer_name'),
            __('app.dashboard.planned_app'),
            __('app.dashboard.sub'),
            __('app.dashboard.address'),
            __('app.dashboard.coordinates'),
            __('app.dashboard.date_created')
        ];
    }

    public function map($entry): array
    {
        return [
            $this->rowCounter++,
            $entry->customer->full_name,
            $entry->plannedApplication->details,
            $entry->subscription->name,
            $entry->installed_address,
            $entry->google_map_coordinates,
            Carbon::parse($entry->created_at)->format(dateHumanReadable()),

        ];
    }

}
