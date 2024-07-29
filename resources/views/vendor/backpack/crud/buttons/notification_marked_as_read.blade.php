@if ($crud->hasAccess('markedAsRead', $entry))

    @if($entry->read_at == null)
        <a 
            href="javascript:void(0)" 
            onclick="markedAsRead(this)" 
            data-route="{{ route('notification.notificationMarkedAsRead', $entry->id) }}" 
            class="btn btn-sm btn-link" 
            data-button-type="markedAsRead"
        >
            <span>
                <i class="las la-check-circle"></i>
                {{ __('Marked as read') }}
            </span>
        </a>
    @endif
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof markedAsRead != 'function') {
	  function markedAsRead(button) {
        var button = $(button);
        var route = button.attr('data-route');

        swal({
            title: "Warning",
            text: "Are you sure you want to mark this item as read?",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "bg-secondary",
                    closeModal: true,
                },
                delete: {
                    text: "Mark as read",
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
                                type: "success",
                                text: result.msg
                            }).show();

                            // Hide the modal, if any
                            $('.modal').modal('hide');
                        } 
                    },
                    error: function(xhr, status, error) {
                        // console.log('Error:', xhr.responseJSON.errors);
                        // Handle validation errors or other errors
                        if (xhr.status === 422) {
                            // Display validation errors to the user
                            var errors = xhr.responseJSON.errors;
                            errors.forEach(function(errorMsg) {
                                // Example: Display error messages using a notification library
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
	// crud.addFunctionToDataTablesDrawEventQueue('markedAsRead');
</script>
@if (!request()->ajax()) @endpush @endif
