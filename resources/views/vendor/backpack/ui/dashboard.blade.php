@extends(backpack_view('blank'))
@php
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
            ->description(__('app.dashboard.registered_customer'))
            ->hint($totalAccounts. __('app.dashboard.total_accounts')); 
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
            ->description(__('app.dashboard.account_connected'))
            ->hint(
                $totalAccountsInstalling.__('app.dashboard.installing').
                $totalAccountsDisconnected.__('app.dashboard.disconnected').
                $totalAccountsNoBilling.__('app.dashboard.no_billing')
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
            ->description(__('app.dashboard.paid_billing'))
            ->hint(
                $unpaidInstallment .__('app.dashboard.installment').
                $unpaidMonthly .__('app.dashboard.monthly_unpaid')
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
            ->description(__('app.dashboard.total_advanced_payment'))
            ->hint(__('app.dashboard.sum_of_advanced'));
    }


    Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
@endphp



@section('content')
{{-- In case widgets have been added to a 'content' group, show those widgets. --}}
@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('group', 'content')->toArray() ])



<div class="card bg-white">
    <div class="card-body">
        
        @include(backpack_view('my_widgets.near_cut_off_accounts'))
        
        <br>
        
        @include(backpack_view('my_widgets.to_be_installed'))
        

    </div>
</div>

@endsection