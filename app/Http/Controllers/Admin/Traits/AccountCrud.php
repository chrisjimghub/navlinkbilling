<?php

namespace App\Http\Controllers\Admin\Traits;

trait AccountCrud
{
    public function accountColumn($label = null)
    {
        $currentTable = $this->crud->model->getTable();

        if (!$this->listColumnExist('account_id')) {
            $this->crud->column('account_id');
        }

        $this->crud->modifyColumn('account_id', [
            'label' => $label ?? __('app.account'),
            'type'  => 'closure',
            'function' => function($entry)  {
                if ($entry->account->details == null) {
                    return;
                }

                return $entry->account->details;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                // $query->orWhereHas($method, function ($q) use ($column, $searchTerm, $relationshipColumn) {
                //     $q->where($relationshipColumn, 'like', '%'.$searchTerm.'%');
                // });
            },
            'orderLogic' => function ($query, $column, $columnDirection) {
                // $table = $this->classInstance($this->convertToClassName($col))->getTable();
                // return $query->leftJoin($table, $table.'.id', '=', $currentTable.'.'.$col.'_id')
                //         ->orderBy($table.'.'.$relationshipColumn, $columnDirection)
                //         ->select($currentTable.'.*');
            },
            'orderable' => true,

            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('account/'.$entry->account_id.'/show');
                },
                // 'target' => '_blank'
            ]
        ]);
    }
}
