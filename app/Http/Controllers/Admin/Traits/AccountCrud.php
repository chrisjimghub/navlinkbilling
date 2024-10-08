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
                // return $query->leftJoin('accounts', 'accounts.id', '=', $currentTable.'.account_id')
                            // ->leftJoin('customers', 'customers.id', '=', 'accounts.customer_id')
                            // ->orderBy('customers.first_name', $columnDirection)
                            // ->orderBy('customers.last_name', $columnDirection)
                            // ->leftJoin('planned_applications', 'planned_applications.id', '=', 'accounts.planned_application_id')
                            // ->leftJoin('locations', 'locations.id', '=', 'planned_applications.location_id')
                            // ->leftJoin('subscriptions', 'subscriptions.id', '=', 'accounts.subscription_id')
                            // ->orderBy('subscriptions.name', $columnDirection) 
                            // ->orderBy('locations.name', $columnDirection)
                            // ->select($currentTable.'.*');

                return $query->whereHas('account.customer', function ($query) use ($columnDirection) {
                    $query->orderBy('last_name', $columnDirection);
                    $query->orderBy('first_name', $columnDirection);
                });
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

    public function accountColumnDetails($label = null)
    {
        $this->accountColumn($label);
        $this->crud->modifyColumn('account_id', [
            'function' => function($entry)  {
                if ($entry->accountDetails) {
                    return $entry->accountDetails;
                }
                
                return;
            },
            'escaped' => false,
            'wrapper' => false
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

    public function accountFieldBilling($label = null)
    {
        $this->accountField($label);
        $this->crud->modifyField('account_id', [
            'options'   => (function ($query) {
                $query->where('subscription_id', '!=', 3); // Piso Wifi
                $query->where('subscription_id', '!=', 4); // Voucher
                return $query->allowedBill()->get();
            }),
        ]);
    }

    public function accountFieldHarvest($label = null)
    {
        $this->accountField($label);
        $this->crud->modifyField('account_id', [
            'options'   => (function ($query) {
                $query->harvestCrud();
                return $query->allowedBill()->get();
            }),
        ]);
    }

    // use in model accesor for account details and billing account snapshot
    public function accountDetails(
        $from,
        $accountId, 
        $name,
        $location,
        $type,
        $subscription,
        $mbps,
        $installedDate,
        $data,
    )
    {
        $anchor = $name;

        if (auth()->user()->can('accounts_show')) {
            $anchor = '<a href='.backpack_url('account/'.$accountId.'/show').'>'.$name.'</a>';
        }

        return 
            '<strong from="'.$from.'" data="'.$data.'">Name: </strong>'.$anchor.'<br/>'.
            '<strong>Location: </strong>' . $location . '<br/>'.
            '<strong>Type: </strong>' . $type . '<br/>'.
            '<strong>Installed: </strong>' . $installedDate . '<br/>'.
            '<strong>Sub: </strong>' . $subscription . '<br/>'.
            '<strong>Mbps: </strong>' . $mbps . '<br/>'.
            '';
    }
}
