<div class="row">
    @canany(['accounts_list'])
        
        <strong class="text-success">
            {{ __('To Be Installed') }}
        </strong>

        @php
            $items = 
                modelInstance('Account')::
                installing()
                ->orderBy('installed_date', 'asc')
                ->simplePaginate(10, ['*'], 'install_page')
                ->appends(request()->except('install_page')); 
            
            $index = ($items->currentPage() - 1) * $items->perPage() + 1;

        @endphp
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>{{ __('app.widgets.priority_num') }}</th>
                    <th>{{ __('app.widgets.account_name') }}</th>
                    <th>{{ __('app.widgets.planned_app') }}</th>
                    <th>{{ __('app.widgets.sub') }}</th>
                    <th>{{ __('app.widgets.coordiantes') }}</th>
                    <th>{{ __('app.widgets.date_installed') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>{{ $item->customer->full_name }}</td>
                        <td>{{ $item->plannedApplication->details }}</td>
                        <td>{{ $item->subscription->name }}</td>
                        <td>{!! coordinatesLink($item->google_map_coordinates) !!}</td>
                        <td>{!! $item->installed_date_badge !!}</td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>

        {{ $items->links() }}
    @endcanany
</div> 