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
                if ($entry->account) {
                    return $entry->account->details;
                }
                
                return;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('account.customer', function ($q) use ($searchTerm) {
                    $q->where('last_name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('first_name', 'like', '%'.$searchTerm.'%');
                });

                $query->orWhereHas('account.subscription', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });

                $query->orWhereHas('account.plannedApplication.location', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
            'orderLogic' => function ($query, $column, $columnDirection) use ($currentTable) {
                return $query->leftJoin('accounts', 'accounts.id', '=', $currentTable.'.account_id')
                            ->leftJoin('customers', 'customers.id', '=', 'accounts.customer_id')
                            ->leftJoin('planned_applications', 'planned_applications.id', '=', 'accounts.planned_application_id')
                            ->leftJoin('locations', 'locations.id', '=', 'planned_applications.location_id')
                            ->leftJoin('subscriptions', 'subscriptions.id', '=', 'accounts.subscription_id')
                            ->orderBy('customers.last_name', $columnDirection)
                            ->orderBy('customers.first_name', $columnDirection)
                            ->orderBy('subscriptions.name', $columnDirection) 
                            ->orderBy('locations.name', $columnDirection)
                            ->select($currentTable.'.*');
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
