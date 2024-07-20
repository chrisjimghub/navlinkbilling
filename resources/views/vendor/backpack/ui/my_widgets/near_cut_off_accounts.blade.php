<div class="row">
    @canany(['accounts_list', 'billings_list'])
        
        <strong class="text-danger">
            {{ __('Near Cut Off Accounts') }}
        </strong>

        <a class="ml-1" href="{{ route('widget.cutOffAccounts') }}">
            {{ __('Download Excel') }} 
        </a>

        @php
            $cutOffItems = 
                modelInstance('Billing')::unpaid()
                ->cutOffAccounts()
                ->orderBy('date_cut_off', 'asc')
                ->simplePaginate(10, ['*'], 'cutoff_page')
                ->appends(request()->except('cutoff_page')); 
            
            $index = ($cutOffItems->currentPage() - 1) * $cutOffItems->perPage() + 1;

        @endphp
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>{{ __('app.widgets.priority_num') }}</th>
                    <th>{{ __('app.widgets.account_name') }}</th>
                    <th>{{ __('app.widgets.planned_app') }}</th>
                    <th>{{ __('app.widgets.sub') }}</th>
                    <th>{{ __('app.widgets.coordinates') }}</th>
                    <th>{{ __('app.widgets.date_cut_off') }}</th>
                    <th>{{ __('app.billing_total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cutOffItems as $item)
                    <tr>
                        {{-- retrieved snapshots instead the relationship, snapshots are method in billing created --}}
                        <td>{{ $index++ }}</td>
                        <td>{{ $item->account_name }}</td>
                        <td>{{ $item->account_planned_application_details }}</td>
                        <td>{{ $item->account_subscription_name }}</td>
                        <td>{!! coordinatesLink($item->account_google_coordinates) !!}</td>
                        <td>{!! $item->date_cut_off_badge !!}</td>
                        <td>{{ currencyFormat($item->total) }}</td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>

        {{ $cutOffItems->links() }}
    @endcanany
</div> 