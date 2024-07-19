<div class="card">
  <div class="card-header" id="filterHeading">
      <button class="btn btn-link ml-auto" type="button" data-toggle="collapse" data-target="#filterForm" aria-expanded="true" aria-controls="filterForm">
          <span class="la la-filter"></span> {{ trans('backpack::crud.filters') }}  
      </button>
  </div>

  <div id="filterForm" class="collapse" aria-labelledby="filterHeading">
      <div class="card-body">
          <form id="filterForm" action="{{ route('billing.index') }}" method="GET">

              <div class="row">
                <div class="form-group col-3">
                    <label for="period">Billing Period</label>
                    <input type="text" id="period" name="period" class="form-control" autocomplete="off"
                          value="{{ Request::get('period') ? Request::get('period') : '' }}">
                </div>
            
  
                <div class="form-group col-2">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">-</option>
                        <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                        <option value="2" {{ Request::get('status') == '2' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                    </select>
                </div>
  
                <div class="form-group col-2">
                    <label for="type">Type</label>
                    <select id="type" name="type" class="form-control">
                        <option value="">-</option>
                        <option value="1" {{ Request::get('type') == '1' ? 'selected' : '' }}>{{ __('Installment Fee') }}</option>
                        <option value="2" {{ Request::get('type') == '2' ? 'selected' : '' }}>{{ __('Monthly Fee') }}</option>
                    </select>
                </div>
              </div>

              <div class="form-group">
                  <a href="{{ route('billing.index') }}" id="remove_filters_button" class="btn btn-secondary">Clear Filters</a>
                  <button type="submit" class="btn btn-primary">Apply Filters</button>
              </div>
          </form>
      </div>
  </div>
</div>




@push('crud_list_styles')
  <link rel="stylesheet" type="text/css" href="{{ basset('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css') }}" />
@endpush



@push('crud_list_scripts')
<script type="text/javascript" src="{{ basset('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ basset('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js') }}"></script>


{{-- clear filters --}}
<script>
  jQuery(document).ready(function($) {
    $("#remove_filters_button").click(function(e) {
      // remove filters from URL
      crud.updateUrl('{{ route("billing.index") }}');
    });
  });
</script>


{{-- date range picker --}}
<script>
  $('input[name="period"]').daterangepicker({
    opens: 'right',
    autoUpdateInput: false, // Prevent automatic input update
    locale: {
        cancelLabel: 'Clear', // Customize clear button text
        format: 'MM/DD/YYYY' // Adjust date format as needed
    }
});

// Handle clear button click event
$('input[name="period"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
}).on('cancel.daterangepicker', function(ev, picker) {
    $(this).val(''); // Clear the input value when canceling selection
});
</script>



@endpush

