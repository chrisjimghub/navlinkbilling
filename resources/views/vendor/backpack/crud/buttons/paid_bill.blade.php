@if ($crud->hasAccess('paidBill'))
    
    @if($entry->billing_status_id == 2)
        <a 
            href="javascript:void(0)" 
            onclick="paidBillEntry(this)" 
            data-route="{{ url($crud->route.'/'.$entry->getKey().'/paidBill') }}" 
            class="btn btn-sm btn-link text-success" 
            data-button-type="paidBill"
            title="Marked as paid?"
            >
                <i class="las la-thumbs-up"></i>
                {{ __('Paid') }}
        </a>
    @endif

@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
    if (typeof paidBillEntry != 'function') {
        $("[data-button-type=paidBill]").unbind('click');

        function paidBillEntry(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var button = $(button);
            var route = button.attr('data-route');

            // $.ajax({
            //     url: route,
            //     type: 'POST',
            //     success: function(result) {
            //         // Show an alert with the result
            //         new Noty({
            //             type: "success",
            //             text: "<strong>Entry cloned</strong><br>A new entry has been added, with the same information as this one."
            //         }).show();

            //         // Hide the modal, if any
            //         $('.modal').modal('hide');

            //         if (typeof crud !== 'undefined') {
            //             crud.table.ajax.reload();
            //         }
            //     },
            //     error: function(result) {
            //         // Show an alert with the result
            //         new Noty({
            //             type: "warning",
            //             text: "<strong>Cloning failed</strong><br>The new entry could not be created. Please try again."
            //         }).show();
            //     }
            // });


            swal({
                title: "{!! trans('backpack::base.warning') !!}",
                text: "{!! __('Are you sure you want to mark this item paid?') !!}",
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
                        text: "{!! __('Paid') !!}",
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
                            if (result == 1) {
                                
                                if (typeof crud !== 'undefined') {
                                    crud.table.ajax.reload();
                                }

                                // Show a success notification bubble
                                new Noty({
                                    type: "success",
                                    text: "{!! '<strong>'.__('Item Paid').'</strong><br>'.__('The item has been marked as paid successfully.') !!}"
                                }).show();

                                // Hide the modal, if any
                                $('.modal').modal('hide');
                            } else {
                                // if the result is an array, it means 
                                // we have notification bubbles to show
                                if (result instanceof Object) {
                                    // trigger one or more bubble notifications 
                                    Object.entries(result).forEach(function(entry, index) {
                                    var type = entry[0];
                                    entry[1].forEach(function(message, i) {
                                        new Noty({
                                            type: type,
                                            text: message
                                        }).show();
                                    });
                                    });
                                } else {// Show an error alert
                                    swal({
                                        title: "{!! __('NOT marked as paid') !!}",
                                        text: "{!! __('There\'s been an error. Your item might not have been marked as paid.') !!}",
                                        icon: "error",
                                        timer: 4000,
                                        buttons: false,
                                    });
                                }			          	  
                            }
                        },
                        error: function(result) {
                            // Show an alert with the result
                            swal({
                                title: "{!! __('NOT marked as paid') !!}",
                                text: "{!! __('There\'s been an error. Your item might not have been marked as paid.') !!}",
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                            });
                        }
                    });
                    }
                });
        }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('paidBillEntry');
</script>
@if (!request()->ajax()) @endpush @endif