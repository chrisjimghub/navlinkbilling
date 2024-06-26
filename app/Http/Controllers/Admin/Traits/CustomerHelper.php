<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\Admin\Traits\CrudColumn;

trait CustomerHelper
{
    use CrudColumn;

    public function showCustomerNameColumn($label = null)
    {
        $currentTable = $this->crud->model->getTable();

        if (!$this->listColumnExist('customer_id')) {
            $this->crud->column('customer_id');
        }

        $this->crud->modifyColumn('customer_id', [
           'type'     => 'closure',
            'function' => function($entry) {
                if ($entry->customer) {
                    // debug($entry->customer);
                    return $entry->customer->fullName;
                }

                return;

            },
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable) {
                return $query->leftJoin('customers', 'customers.id', '=', $currentTable.'.customer_id')
                        ->orderBy('customers.last_name', $columnDirection)
                        ->orderBy('customers.first_name', $columnDirection)
                        ->select($currentTable.'.*');
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('customer', function ($q) use ($column, $searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                      ->orWhere('first_name', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        if ($label) {
            $this->crud->modifyColumn('customer_id', [
                'label' => $label
            ]);
        }
    }
}
