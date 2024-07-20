<div class="form-group {{ $filter['class-col'] ?? 'col-3' }}">
    <label for="{{ $filter['name'] }}">{{ $filter['label'] }}</label>
    <input type="text" id="{{ $filter['name'] }}" name="{{ $filter['name'] }}" class="form-control" autocomplete="off"
        value="{{ Request::get($filter['name']) ? Request::get($filter['name']) : '' }}">
</div>



@push('crud_list_styles')
  <link rel="stylesheet" type="text/css" href="{{ basset('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css') }}" />
@endpush

@push('crud_list_scripts')
<script type="text/javascript" src="{{ basset('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ basset('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js') }}"></script>

<script>
    $('input[name="{{ $filter['name'] }}"]').daterangepicker({
      opens: 'right',
      autoUpdateInput: false, // Prevent automatic input update
      locale: {
          cancelLabel: 'Clear', // Customize clear button text
          format: 'MM/DD/YYYY' // Adjust date format as needed
      }
  });
  
  // Handle clear button click event
  $('input[name="{{ $filter['name'] }}"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  }).on('cancel.daterangepicker', function(ev, picker) {
      $(this).val(''); // Clear the input value when canceling selection
  });
</script>

@endpush