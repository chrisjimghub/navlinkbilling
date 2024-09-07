<?php

namespace App\Http\Controllers\Admin\FilterQueries;
use App\Http\Controllers\Admin\Traits\FetchOptions;

trait StatusColumnFilterQueries
{
    use FetchOptions;

    public function statusColumnFilterQueries($query)
    {
        $status = request()->input('status');

        if ($status) {
            $query->where('status_id', $status);
        }
    }

    public function statusColumnFilterField()
    {
        $this->crud->field([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select_from_array',
            'options' => $this->statusLists(),
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ]
        ]);
    }
    
}
