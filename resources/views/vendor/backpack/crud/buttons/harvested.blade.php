@if ($crud->hasAccess('harvested') && $entry->isUnharvested())
    <a href="javascript:void(0)" onclick="harvestEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/harvested') }}" class="btn btn-sm btn-link text-success" data-button-type="harvest">
        <span><i class="las la-check"></i> 
            {{ __('Harvest') }}
        </span>
    </a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
	if (typeof harvestEntry != 'function') {
	  $("[data-button-type=harvest]").unbind('click');

	  function harvestEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');

		swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "{!! trans('app.wifi_harvest.mark_harvest_msg') !!}",
		  icon: "warning",
		  buttons: {
		  	cancel: {
				text: "{!! trans('backpack::crud.cancel') !!}",
				value: null,
				visible: true,
				className: "bg-secondary",
				closeModal: true,
			},
			delete: {
				text: "{!! trans('app.wifi_harvest.mark_harvest') !!}",
				value: true,
				visible: true,
				className: "bg-success",
				},
			},
		  dangerMode: true,
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'POST',
			      success: function(result) {
                        if (result.msg) {
                            if (typeof crud !== 'undefined') {
                                crud.table.ajax.reload();
                            }
                            // Show a success notification bubble
                            new Noty({
                                type: result.type,
                                text: result.msg
                            }).show();
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors or other errors
                        if (xhr.status === 422) {
                            // Display validation errors to the user
                            var errors = xhr.responseJSON.errors;
                            errors.forEach(function(errorMsg) {
                                new Noty({
                                    text: errorMsg,
                                    type: 'error'
                                }).show();
                            });
                        } else {
                            // Handle other types of errors
                            swalError('Please contact the administrator.')
                        }
                    }
			  });
			}
		});

      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('harvestEntry');
</script>
@if (!request()->ajax()) @endpush @endif
