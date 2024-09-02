<?php

namespace App\Http\Controllers\Admin\FilterQueries;

use Illuminate\Support\Carbon;

trait DateColumnFilterQueries
{
    public function dateColumnFilterQueries($query)
    {
        $dates = request()->input('date');
        if ($dates) {
            $dates = explode('-', $dates);
            $dateStart = Carbon::parse($dates[0]);
            $dateEnd = Carbon::parse($dates[1]);
            $query->whereBetween('date', [$dateStart, $dateEnd]);
        }
    }

    public function dateColumnFilterField()
    {
        $this->crud->field([
            'name' => 'date',
            'type' => 'date_range',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
    }

}
