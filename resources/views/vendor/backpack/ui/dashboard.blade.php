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
        $totalCustomers = classInstance('Customer')::count();
        $totalAccounts = classInstance('Account')::count();

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
        $totalAccounts = classInstance('Account')::count();
        $totalAccountsConnected = classInstance('Account')::connected()->count();
        $totalAccountsInstalling = classInstance('Account')::installing()->count();
        $totalAccountsDisconnected = classInstance('Account')::disconnected()->count();

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
                $totalAccountsDisconnected.' disconnected.'
            );
    }
    
    if (auth()->user()->can('billings_list')) {
        $unpaidBillings = classInstance('Billing')::unpaid()->count();
        $unpaidInstallment = classInstance('Billing')::where('billing_type_id', 1)->unpaid()->count();
        $unpaidMonthly = classInstance('Billing')::where('billing_type_id', 2)->unpaid()->count();
        $totalBillings = classInstance('Billing')::count();
        $paidBillings = classInstance('Billing')::paid()->count();
        
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
                number_format(classInstance('AccountCredit')::sum('amount'))
            )
            ->description('Total Advanced Payment.')
            ->hint('Sum of all customers advanced.');
    }


    Widget::add()->to('before_content')->type('div')->class('row')->content($contents);
@endphp



@section('content')


<div class="card bg-white">
    <div class="card-body">
        <div class="row">
                
            @canany(['accounts_list', 'billings_list'])
                
                <strong class="text-danger">
                    {{ __('Near Cut Off Accounts') }}
                </strong>

                @php
                    $cutOffItems = classInstance('Billing')::unpaid()
                                                    ->monthly()
                                                    ->orderBy('date_cut_off', 'asc')
                                                    // ->get();
                                                    ->simplePaginate(10); 
                    
                    $index = ($cutOffItems->currentPage() - 1) * $cutOffItems->perPage() + 1;

                @endphp
                <table id="dummyTable" class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ __('Priority #') }}</th>
                            <th>{{ __('Account Name') }}</th>
                            <th>{{ __('Planned Application') }}</th>
                            <th>{{ __('Subscription') }}</th>
                            <th>{{ __('Coordinates') }}</th>
                            <th>{{ __('Cut Off Date') }}</th>
                            <th>{{ __('app.billing_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cutOffItems as $item)
                            <tr>
                                <td>{{ $index++ }}</td>
                                <td>{{ $item->account->customer->full_name }}</td>
                                <td>{{ $item->account->plannedApplication->details }}</td>
                                <td>{{ $item->account->subscription->name }}</td>
                                <td>
                                    <a href="{{ "https://www.google.com/maps?q=". $item->account->google_map_coordinates }}"
                                        target="_blank"    
                                    >
                                        {{ $item->account->google_map_coordinates }}
                                    </a>
                                    
                                </td>
                                <td>
                                    {!! $item->date_cut_off_badge !!}
                                </td>
                                <td class="">
                                    {{ currencyFormat($item->total) }}
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>

                {{ $cutOffItems->links() }}
            @endcanany
        </div>
    </div>
</div>




@endsection