<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\Admin\Traits\UtilityHelper;
use App\Http\Controllers\Admin\Traits\CurrencyFormat;

trait CrudColumn
{
    use CurrencyFormat;
    use UtilityHelper;

    public function listColumnExist($columnName)
    {
        // check if column exist in list operation
        if (
            !array_key_exists('list.columns', $this->crud->settings()) || // no columns yet
            !array_key_exists($columnName, $this->crud->settings()['list.columns']) // column not yet created
        ) {
            return false;
        }

        return true;
    }

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

    public function relationshipColumn($column, $label = null, $relationshipColumn = 'name',)
    {
        $col = str_replace('_id', '', $column);
        $method = $this->relationshipMethodName($col);
        $currentTable = $this->crud->model->getTable();

        if (!$this->listColumnExist($column)) {
            $this->crud->column($column);
        }

        $this->crud->modifyColumn($column, [
            'label' => $label ?? $this->convertColumnToHumanReadable($col),
            'type'  => 'closure',
            'function' => function($entry) use ($method, $relationshipColumn) {
                if ($entry->{$method} == null) {
                    return;
                }
                return $entry->{$method}->{$relationshipColumn};
            },
            'searchLogic' => function ($query, $column, $searchTerm) use ($method, $relationshipColumn) {
                $query->orWhereHas($method, function ($q) use ($column, $searchTerm, $relationshipColumn) {
                    $q->where($relationshipColumn, 'like', '%'.$searchTerm.'%');
                });
            },
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable, $col, $relationshipColumn) {
                $table = $this->classInstance($this->convertToClassName($col))->getTable();
                return $query->leftJoin($table, $table.'.id', '=', $currentTable.'.'.$col.'_id')
                        ->orderBy($table.'.'.$relationshipColumn, $columnDirection)
                        ->select($currentTable.'.*');
            },
            'orderable' => true,
        ]);
    }

    public function currencyColumn($column, $label = null)
    {
        if (!$this->listColumnExist($column)) {
            $this->crud->column($column);
        }
        
        $this->currencyFormatColumn($column);
    }

}

