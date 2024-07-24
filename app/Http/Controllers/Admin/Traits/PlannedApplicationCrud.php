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

            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('plannedApplication', function ($query) use ($searchTerm) {
                    $query->whereHas('location', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%'.$searchTerm.'%');
                    })
                    ->orWhereHas('plannedApplicationType', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%'.$searchTerm.'%');
                    })
                    ->orWhere(function ($q) use ($searchTerm) {
                        $q->where('mbps', 'like', '%'.$searchTerm.'%')
                          ->orWhere('price', 'like', '%'.$searchTerm.'%');
                    });
                });
            },

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

    // this is to reverse the getDetailsAttribute in planned application model, it is use for whereClause using the details
    public function parseDetails($details)
    {
        // Split by the ' - ' separator for Location and PlannedApplicationType
        $parts = explode(' - ', $details);
        
        if (count($parts) !== 2) {
            // Handle unexpected format
            return null;
        }

        // Extract Location and PlannedApplicationType
        $location = $parts[0];
        $applicationType = $parts[1];

        // Further split the PlannedApplicationType by ' : ' separator to get the type and the rest
        $applicationParts = explode(' : ', $applicationType);

        if (count($applicationParts) !== 2) {
            // Handle unexpected format
            return null;
        }

        // Extract PlannedApplicationType, Mbps, and Price
        $plannedApplicationType = $applicationParts[0];
        $detailsRest = $applicationParts[1];

        // Use regex to extract Mbps and Price from the rest
        preg_match('/(\d+)Mbps/', $detailsRest, $mbpsMatches);
        preg_match('/₱([\d,\.]+)/', $detailsRest, $priceMatches);

        $mbps = isset($mbpsMatches[1]) ? (int)$mbpsMatches[1] : null;
        $price = isset($priceMatches[1]) ? (float)str_replace(',', '', $priceMatches[1]) : null;

        return [
            'location' => $location,
            'plannedApplicationType' => $plannedApplicationType,
            'mbps' => $mbps,
            'price' => $price, // Updated to use 'price'
        ];
    }


}
