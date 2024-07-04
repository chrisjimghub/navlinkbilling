@extends(backpack_view('blank'))

@php
    if (backpack_theme_config('show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => backpack_view('inc.getting_started'),
        ];
    } else {
        // $widgets['before_content'][] = [
        //     'type'        => 'jumbotron',
        //     'heading'     => trans('backpack::base.welcome'),
        //     'heading_class' => 'display-3 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
        //     'content'     => trans('backpack::base.use_sidebar'),
        //     'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
        //     'button_link' => backpack_url('logout'),
        //     'button_text' => trans('backpack::base.logout'),
        // ];
    }


    $contents = [];

    if (auth()->user()->can('customers_list')) {
        $totalCustomers = modelInstance('Customer')::count();
        $totalAccounts = modelInstance('Account')::count();

        $contents[] = 
            Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-info my-widgets mb-2')
            ->progressClass('progress-bar')
            ->progress(round(
                $totalAccounts / ($totalCustomers == 0 ? 1 : $totalCustomers) * 100
            ))
            ->value($totalCustomers)
            ->description('Registered Customers.')
            ->hint($totalAccounts.' total accounts.'); 
    }

    if (auth()->user()->can('accounts_list')) {
        $totalAccounts = modelInstance('Account')::count();
        $totalAccountsConnected = modelInstance('Account')::connected()->count();
        $totalAccountsInstalling = modelInstance('Account')::installing()->count();
        $totalAccountsDisconnected = modelInstance('Account')::disconnected()->count();
        $totalAccountsNoBilling = modelInstance('Account')::connectedNoBilling()->count();

        $contents[] = 
            Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-success my-widgets mb-2')
            ->progressClass('progress-bar')
            ->progress(round(
                // connected accounts / total accounts * 100
                $totalAccountsConnected / ($totalAccounts == 0 ? 1 : $totalAccounts) * 100
            ))
            ->value($totalAccountsConnected)
            ->description('Accounts Connected.')
            ->hint(
                $totalAccountsInstalling.' installing, '.
                $totalAccountsDisconnected.' disconnected, '.
                $totalAccountsNoBilling.' no billing.'
            );
    }
    
    if (auth()->user()->can('billings_list')) {
        $unpaidBillings = modelInstance('Billing')::unpaid()->count();
        $unpaidInstallment = modelInstance('Billing')::where('billing_type_id', 1)->unpaid()->count();
        $unpaidMonthly = modelInstance('Billing')::where('billing_type_id', 2)->unpaid()->count();
        $totalBillings = modelInstance('Billing')::count();
        $paidBillings = modelInstance('Billing')::paid()->count();
        
        $contents[] = 
            Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-dark my-widgets mb-2')
            ->progressClass('progress-bar')
            ->progress(round(
                $paidBillings / ($totalBillings == 0 ? 1 : $totalBillings) * 100
            ))
            ->value($paidBillings)
            ->description('Paid Billings.')
            ->hint(
                $unpaidInstallment .' installment, '.
                $unpaidMonthly .' monthly unpaid.'
            );
    }


    if (auth()->user()->can('account_credits_list')) {
        $contents[] = 
            Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-warning my-widgets mb-2')
            ->progressClass('progress-bar')
            ->progress(100)
            ->value(
                number_format(modelInstance('AccountCredit')::sum('amount'))
            )
            ->description('Total Advanced Payment.')
            ->hint('Sum of all customers advanced.');
    }


    Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
@endphp



@section('content')


<div class="card bg-white">
    <div class="card-body">

        @include(backpack_view('my_widgets.near_cut_off_accounts'))
        
        <br>
        
        @include(backpack_view('my_widgets.to_be_installed'))
        

    </div>
</div>




@endsection