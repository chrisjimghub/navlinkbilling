<div class="row">
    @canany(['accounts_list', 'billings_list'])
        
        <strong class="text-danger">
            {{ __('Near Cut Off Accounts') }}
        </strong>

        @php
            $cutOffItems = 
                modelInstance('Billing')::unpaid()
                ->monthly()
                ->whereBetween('date_cut_off', [
                    carbonToday()->subDays(5), 
                    carbonToday()->addDays(5)
                ])
                ->orderBy('date_cut_off', 'asc')
                ->simplePaginate(10); 
            
            $index = ($cutOffItems->currentPage() - 1) * $cutOffItems->perPage() + 1;

        @endphp
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>{{ __('app.widgets.priority_num') }}</th>
                    <th>{{ __('app.widgets.account_name') }}</th>
                    <th>{{ __('app.widgets.planned_app') }}</th>
                    <th>{{ __('app.widgets.sub') }}</th>
                    <th>{{ __('app.widgets.coordiantes') }}</th>
                    <th>{{ __('app.widgets.date_cut_off') }}</th>
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
                        <td>{!! coordinatesLink($item->google_map_coordinates) !!}</td>
                        <td>{!! $item->date_cut_off_badge !!}</td>
                        <td>{{ currencyFormat($item->total) }}</td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>

        {{ $cutOffItems->links() }}
    @endcanany
</div> 