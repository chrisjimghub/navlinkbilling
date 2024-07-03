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
        $totalCustomers = \App\Models\Customer::count();
        $totalAccounts = \App\Models\Account::count();

        $contents[] = 
            Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-info my-widgets mb-2')
            ->progressClass('progress-bar')
            ->progress(round(
                $totalAccounts / $totalCustomers * 100
            ))
            ->value($totalCustomers)
            ->description('Registered Customers.')
            ->hint($totalAccounts.' total accounts.'); 
    }

    if (auth()->user()->can('accounts_list')) {
        $totalAccounts = \App\Models\Account::count();
        $totalAccountsConnected = \App\Models\Account::connected()->count();
        $totalAccountsInstalling = \App\Models\Account::installing()->count();
        $totalAccountsDisconnected = \App\Models\Account::disconnected()->count();

        $contents[] = 
            Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-success my-widgets mb-2')
            ->progressClass('progress-bar')
            ->progress(round(
                // connected accounts / total accounts * 100
                $totalAccountsConnected / $totalAccounts * 100
            ))
            ->value($totalAccountsConnected)
            ->description('Accounts Connected.')
            ->hint(
                $totalAccountsInstalling.' installing, '.
                $totalAccountsDisconnected.' disconnected.'
            );
    }
    
    if (auth()->user()->can('billings_list')) {
        $unpaidBillings = \App\Models\Billing::unpaid()->count();
        $unpaidInstallment = \App\Models\Billing::where('billing_type_id', 1)->unpaid()->count();
        $unpaidMonthly = \App\Models\Billing::where('billing_type_id', 2)->unpaid()->count();
        $totalBillings = \App\Models\Billing::count();
        $paidBillings = \App\Models\Billing::paid()->count();
        
        $contents[] = 
            Widget::make()
            ->type('progress')
            ->class('card border-0 text-white bg-dark my-widgets mb-2')
            ->progressClass('progress-bar')
            ->progress(round(
                $paidBillings / $totalBillings * 100
            ))
            ->value($paidBillings)
            ->description('Paid Billings.')
            ->hint(
                $unpaidInstallment .' installment, '.
                $unpaidMonthly .' monthly.'
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
                number_format(\App\Models\AccountCredit::sum('amount'))
            )
            ->description('Total Advanced Payment.')
            ->hint('Sum of all customers advanced.');
    }


    Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
@endphp



@section('content')
@endsection
