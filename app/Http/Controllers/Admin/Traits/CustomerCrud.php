<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\Admin\Traits\UtilityHelper;

trait CustomerCrud
{
    use UtilityHelper;

    // not relationship
    public function customerNameCol($label)
    {
        $this->crud->column([
            'name' => 'full_name',
            'label' => $label ?? __('app.customer'),
            'type' => 'text',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('first_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('last_name', 'like', '%'.$searchTerm.'%');
            },
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query
                        ->orderBy('last_name', $columnDirection)
                        ->orderBy('first_name', $columnDirection)
                        ->select('*');
            },
            'orderable' => true,
        ]);
    }

    // relationship
    public function customerNameColumn($label = null)
    {
        $currentTable = $this->crud->model->getTable();

        if ($currentTable == 'customers') {
            return $this->customerNameCol($label);
        } 

        if (!$this->listColumnExist('customer_id')) {
            $this->crud->column('customer_id');
        }

        $this->crud->modifyColumn('customer_id', [
            'label' => $label ?? __('app.customer'),
            'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->customer) {
                    // debug($entry->customer);
                    return $entry->customer->fullName;
                }
                return;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('customer', function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%');
                });
            },
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable) {
                return $query->leftJoin('customers', 'customers.id', '=', $currentTable.'.customer_id')
                        ->orderBy('customers.last_name', $columnDirection)
                        ->orderBy('customers.first_name', $columnDirection)
                        ->select($currentTable.'.*');
            },
            'orderable' => true,
        ]);

        if ($label) {
            $this->crud->modifyColumn('customer_id', [
                'label' => $label
            ]);
        }
    }

    

}

