@if ($crud->hasAccessToAny([
    'filters',
    'export',
]))
<div class="card">
  <div class="card-header" id="filterHeading">

        @if($crud->hasAccess('filters'))
            <button class="btn btn-link ml-auto" type="button" data-toggle="collapse" data-target="#filterForm" aria-expanded="true" aria-controls="filterForm">
                <span class="la la-filter"></span> {{ trans('backpack::crud.filters') }}  
            </button>
        @endif

        @if($crud->hasAccess('export'))
            <button id="export-button" class="btn btn-link ml-n2" type="button">
                <span class="la la-download"></span> {{ __('Export') }}  
            </button>
        @endif

  </div>


  @if ($crud->hasAccess('filters'))
    <div id="filterForm" class="collapse" aria-labelledby="filterHeading">
        <div class="card-body">
            <form action="{{ route(strtolower($crud->entity_name).'.index') }}" method="GET">

                @php
                    $chunkedFilters = collect($crud->myFilters())->chunk(3);
                @endphp

                @foreach ($chunkedFilters as $filterChunk)

                    <div class="row">

                        @foreach ($filterChunk as $filter)
                            @include('crud::filters.custom.'.$filter['type'])    
                        @endforeach
                        
                    </div>

                @endforeach

                <div class="form-group">
                    <a href="{{ route(strtolower($crud->entity_name).'.index') }}" id="remove_filters_button" class="btn btn-secondary">Clear Filters</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>

            </form>
        </div>
    </div>
  @endif


</div>






@push('crud_list_scripts')
{{-- clear filters --}}
<script>
  jQuery(document).ready(function($) {
    $("#remove_filters_button").click(function(e) {
      // remove filters from URL
      crud.updateUrl('{{ route("billing.index") }}');
    });
  });
</script>

{{-- dont collapse filter if it has get request --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Function to check if URL has query parameters
    function hasQueryParameters() {
        return window.location.search.length > 0;
    }

    // Function to open the collapsible filter form
    function openFilterForm() {
        var filterFormContainer = document.getElementById('filterForm');
        if (filterFormContainer) {
            new bootstrap.Collapse(filterFormContainer, {
                toggle: true
            });
        }
    }

    // Check if there are query parameters and open the filter form
    if (hasQueryParameters()) {
        openFilterForm();
    }
});

</script>

@if($crud->hasAccess('export'))
<script>
    document.getElementById('export-button').addEventListener('click', function() {
        // Get the current URL
        const currentUrl = new URL(window.location.href);
        
        // Get the query parameters from the current URL
        const queryParams = currentUrl.searchParams;
        
        // Create the export URL
        const exportUrl = new URL('{{ route(strtolower($crud->entity_name).'.export') }}', window.location.origin);
        
        // Append all current query parameters to the export URL
        queryParams.forEach((value, key) => {
            exportUrl.searchParams.append(key, value);
        });
        
        // Redirect to the export URL
        window.location.href = exportUrl.toString();
    });
</script>
@endif


@endpush

@endif
{{-- endIf from hasAnyAccess --}}