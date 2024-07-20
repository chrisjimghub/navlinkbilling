<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Rules\DateRangePicker;
use Illuminate\Support\Facades\Validator;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait MyFiltersOperation
{
    protected $myFilters = [
        
    ];

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupMyFiltersDefaults()
    {
        CRUD::allowAccess('filters');

        CRUD::operation('myFilters', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        $this->myFilters();
        $filters = $this->myFilters;
        $this->crud->macro('myFilters', function() use ($filters) {
            return $filters;
        });

    }

    protected function myFilters()
    {
        // use this method in controller and define the filter like this
        // ex.
        // $this->myFilter([
        //     'name' => 'period',
        //     'label' => __('Billing Period'),
        //     'type' => 'date_range',
        // ]);
    }

    private function myFilter(array $data)
    {
        $this->myFilters[] = $data;
    }

    private function myFiltersValidation()
    {   
        // If no access to filters, then don't proceed but don't show an error.
        if (!$this->crud->hasAccess('filters')) {
            return false;
        }

        $validationErrors = [];

        foreach ($this->myFilters as $filter) {

            $filterName = $filter['name'];
            $filterValue = request()->input($filterName);
            
            if ($filter['type'] == 'date_range') {
                $validator = Validator::make([$filterName => $filterValue], [
                    $filterName => [
                        'nullable',
                        new DateRangePicker(),
                    ],
                ]);    
        
                if ($validator->fails()) {
                    $validationErrors = array_merge($validationErrors, $validator->errors()->all());
                }

            } elseif ($filter['type'] == 'select') {
                $validator = Validator::make([$filterName => $filterValue], [
                    $filterName => [
                        'nullable',
                        'integer',
                        'in:' . implode(',', array_keys($filter['options'])), // Dynamic options here
                    ],
                ]);    
        
                if ($validator->fails()) {
                    $validationErrors = array_merge($validationErrors, $validator->errors()->all());
                }
            }
        }

        // Show all validation errors if any
        if (!empty($validationErrors)) {
            \Alert::error($validationErrors);
            return false;
        }

        return true;
    }


}