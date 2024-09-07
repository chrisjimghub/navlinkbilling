<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Billing;
use App\Models\HotspotVoucher;
use Illuminate\Support\Carbon;
use Backpack\CRUD\app\Library\Widget;

trait Widgets
{
    public function widgetWifiHarvest()
    {
        if ($this->crud->getOperation() == 'list') {

            $billing = Billing::whereHas('account', function ($query) {
                $query->harvestCrud();
            });

            $date = Carbon::now();
            $month = $date->month;
            $year = $date->year;

            $billingSchedule = clone $billing;
            $billingHarvested = clone $billing;
            $totalSchedule = $billingSchedule->whereDate('date_start', $date)->count();
            $harvested = $billingHarvested->whereDate('date_start', $date)->harvested()->count();
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $harvested.'/'.$totalSchedule,
                'description'   => __('app.widget.todays_unit_harvest'),
                'progress'      => widgetProgress($harvested, $totalSchedule), 
                'progressClass' => 'progress-bar bg-success',
                'hint'          => 'Piso Wi-Fi units must be collected today.',
            ];

            $billingForDaily = clone $billing;
            $total = $billingForDaily->whereDate('date_start', $date)->harvested()->get()->sum('total');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.todays_harvest'),
                'progress'      => widgetProgress(now()->hour, 24), 
                'progressClass' => 'progress-bar bg-info',
                'hint'          => now()->format(dateHumanReadable()),
            ];

            $billingForMonth = clone $billing;
            $total = $billingForMonth->whereMonth('date_start', $month)->harvested()->get()->sum('total');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.months_harvest'),
                'progress'      => widgetProgress(now()->day, now()->daysInMonth()), 
                'progressClass' => 'progress-bar bg-warning',
                'hint'          => now()->format('M, Y'),
            ];

            $billingForYear = clone $billing;
            $total = $billingForYear->whereYear('date_start', $year)->harvested()->get()->sum('total');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.years_harvest'),
                'progress'      => widgetProgress(now()->month, 12), 
                'progressClass' => 'progress-bar bg-dark',
                'hint'          => 'Jan - Dec '.date('Y'),
            ];

            Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
        }
    }

    public function widgetHotspotVoucher()
    {
        if ($this->crud->getOperation() == 'list') {

            $date = Carbon::now();
            $month = $date->month;
            $year = $date->year;

            $total = HotspotVoucher::paid()->whereDate('date', $date)->get()->sum('amount');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.todays_voucher_income'),
                'progress'      => widgetProgress(now()->hour, 24), 
                'progressClass' => 'progress-bar bg-info',
                'hint'          => now()->format(dateHumanReadable()),
            ];

            $total = HotspotVoucher::paid()->whereMonth('date', $month)->get()->sum('amount');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.months_voucher_income'),
                'progress'      => widgetProgress(now()->day, now()->daysInMonth()), 
                'progressClass' => 'progress-bar bg-warning',
                'hint'          => now()->format('M, Y'),
            ];

            $total = HotspotVoucher::paid()->whereYear('date', $year)->get()->sum('amount');
            $contents[] = [
                'type'          => 'progress_white',
                'class'         => 'card mb-3',
                'value'         => $this->currencyFormatAccessor($total),
                'description'   => __('app.widget.years_voucher_income'),
                'progress'      => widgetProgress(now()->month, 12), 
                'progressClass' => 'progress-bar bg-dark',
                'hint'          => 'Jan - Dec '.date('Y'),
            ];

            Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
        }
    }
}
