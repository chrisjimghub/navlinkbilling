<?php

namespace App\Http\Controllers\Admin\Traits;

trait PlannedApplicationCrud
{
    public function plannedApplicationColumn($label = null)
    {
        $currentTable = $this->crud->model->getTable();

        $column = 'plannedApplication';

        if (!$this->listColumnExist($column)) {
            $this->crud->column($column);
        }

        $this->crud->modifyColumn($column, [
            'label' => $label ?? __('app.planned_application'),
            'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->plannedApplication) {
                    return $entry->plannedApplication->details;
                }
                return;
            },
            // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     $query->orWhereHas('customer', function ($q) use ($column, $searchTerm) {
            //         $q->where('last_name', 'like', '%'.$searchTerm.'%')
            //           ->orWhere('first_name', 'like', '%'.$searchTerm.'%');
            //     });
            // },

            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable) {
                $query->leftJoin('planned_applications', 'planned_applications.id', '=', $currentTable.'.planned_application_id')
                        ->leftJoin('locations', 'locations.id', '=', 'planned_applications.location_id')
                        ->leftJoin('planned_application_types', 'planned_application_types.id', '=', 'planned_applications.planned_application_type_id')
                        ->orderBy('locations.name', $columnDirection)
                        ->orderBy('planned_application_types.name', $columnDirection)
                        ->orderBy('planned_applications.mbps', $columnDirection)
                        ->orderBy('planned_applications.price', $columnDirection)
                        ->select($currentTable.'.*');
                
                // debug($query->toSql());

                return $query;
            },
            'orderable' => true,
        ]);
        
    }
}
