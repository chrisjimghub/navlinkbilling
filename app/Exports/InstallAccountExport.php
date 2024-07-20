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
            '#',
            __('app.widgets.account_name'),
            __('app.widgets.planned_app'),
            __('app.widgets.sub'),
            __('app.widgets.address'),
            __('app.widgets.coordinates'),
            __('app.widgets.date_created')
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
