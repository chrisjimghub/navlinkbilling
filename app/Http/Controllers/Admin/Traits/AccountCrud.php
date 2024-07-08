<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Models\Account;

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

    public function accountField($label = null)
    {
        $this->crud->field([
            'type'      => 'select',
            'name'      => 'account_id', // the db column for the foreign key
            'label'     => $label ?? __('app.account'),
            'allows_null' => true,

            // optional
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'account',
        
            // optional - manually specify the related model and attribute
            'model'     => Account::class, // related model
            'attribute' => 'details', // foreign key attribute that is shown to user
        
            // optional - force the related options to be a custom query, instead of all();
            'options'   => (function ($query) {
                return $query->allowedBill()->get(); // use the local scope
                // return $query->notDisconnected()->get(); // use the local scope
            }), // you can use this to filter the results shown in the select
        ]);
    }

    // use in model accesor for account details and billing account snapshot
    public function accountDetails(
        $from,
        $id, 
        $name,
        $location,
        $type,
        $subscription,
        $mbps,
        $installedDate,
        $dailyRate,
    )
    {
        return 
            '<strong from="'.$from.'" daily-rate="'.$dailyRate.'">Name: </strong><a href='.backpack_url('account/'.$id.'/show').'>'.$name.'</a><br/>'.
            '<strong>Location: </strong>' . $location . '<br/>'.
            '<strong>Type: </strong>' . $type . '<br/>'.
            '<strong>Installed: </strong>' . $installedDate . '<br/>'.
            '<strong>Sub: </strong>' . $subscription . '<br/>'.
            '<strong>Mbps: </strong>' . $mbps . '<br/>'.
            '';
    }
}
