<div class="card">
  <div class="card-header" id="filterHeading">
        <button class="btn btn-link ml-auto" type="button" data-toggle="collapse" data-target="#filterForm" aria-expanded="true" aria-controls="filterForm">
          <span class="la la-filter"></span> {{ trans('backpack::crud.filters') }}  
        </button>
  </div>

  <div id="filterForm" class="collapse" aria-labelledby="filterHeading">
      <div class="card-body">
          <form id="filterForm" action="{{ route('billing.index') }}" method="GET">

              <div class="form-group">
                  <label for="status">Status</label>
                  <select id="status" name="status" class="form-control">
                      <option value="">-</option>
                      <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                      <option value="2" {{ Request::get('status') == '2' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                  </select>
              </div>


              <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" class="form-control">
                    <option value="">-</option>
                    <option value="1" {{ Request::get('type') == '1' ? 'selected' : '' }}>{{ __('Installment Fee') }}</option>
                    <option value="2" {{ Request::get('type') == '2' ? 'selected' : '' }}>{{ __('Monthly Fee') }}</option>
                </select>
            </div>

              <div class="form-group">
                <a href="{{ route('billing.index') }}" id="remove_filters_button" class="btn btn-secondary">Clear Filters</a>
                  <button type="submit" class="btn btn-primary">Apply Filters</button>
              </div>
          </form>
      </div>
  </div>

</div>

@push('crud_list_scripts')
<script>
jQuery(document).ready(function($) {
  $("#remove_filters_button").click(function(e) {
    // remove filters from URL
    crud.updateUrl('{{ route("billing.index") }}');
  });
});
</script>
@endpush

