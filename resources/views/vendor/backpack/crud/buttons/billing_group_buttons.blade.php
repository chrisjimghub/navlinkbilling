@if ($crud->hasAccessToAny([
        'pay', 
        'payUsingCredit', 
        'upgradePlan',
        'serviceInterrupt',
        'sendNotification',
    ]))
    
    @if($entry->isUnpaid())
        <div class="btn-group">
            <button type="button" class="btn text-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Operations <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                
                @if($crud->hasAccess('pay'))
                    <li>
                        <a 
                            href="javascript:void(0)" 
                            onclick="pay(this)" 
                            data-route="{{ url($crud->route.'/'.$entry->getKey().'/pay') }}" 
                            class="btn btn-sm btn-link text-success" 
                            data-button-type="pay"
                            title="Marked as paid?"
                            >
                                <i class="las la-thumbs-up"></i>
                                {{ __('Pay') }}
                        </a>
                    </li>    
                @endif
                
                {{-- TODO:: --}}
                @if($crud->hasAccess('payUsingCredit'))
                    <li>
                        <a 
                            href="javascript:void(0)" 
                            onclick="payUsingCredit(this)" 
                            data-route="{{ url($crud->route.'/'.$entry->getKey().'/payUsingCredit') }}" 
                            class="btn btn-sm btn-link text-warning" 
                            data-button-type="payUsingCredit"
                            title="Marked as paid using credit?"
                            >
                                <i class="las la-credit-card"></i>
                                {{ __('Pay Using Credit') }}
                        </a>

                    </li>
                @endif

                {{-- TODO:: --}}
                @if($crud->hasAccess('upgradePlan'))
                    <li>
                        <a 
                            href="javascript:void(0)" 
                            {{-- onclick="payEntry(this)"  --}}
                            {{-- data-route="{{ url($crud->route.'/'.$entry->getKey().'/pay') }}"  --}}
                            class="btn btn-sm btn-link text-info" 
                            {{-- data-button-type="pay" --}}
                            {{-- title="Marked as paid?" --}}
                            >
                                <i class="las la-level-up-alt"></i>
                                {{ __('Upgrade Plan') }}
                        </a>
                    </li>
                @endif

                @if($crud->hasAccess('serviceInterrupt') && $entry->isMonthlyFee())
                    <li>
                        <!-- Trigger Button -->
                        <a 
                            href="javascript:void(0)" 
                            class="btn btn-sm btn-link text-danger" 
                            id="openServiceInterruptModal"
                            onclick="serviceInterruptModal(this)"
                            data-button-type="serviceInterruptModal"
                            data-billing-id={{ $entry->getKey() }}
                        >
                            <i class="las la-exclamation-triangle"></i> Service Interruption
                        </a>
                    </li>

                    <!-- Modal -->
                    <div class="modal fade" id="serviceInterruptModal-{{ $entry->getKey() }}" tabindex="-1" role="dialog" aria-labelledby="serviceInterruptModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="serviceInterruptModalLabel">Service Interruption</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="account_details">
                                            <strong>Account</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="account_details" name="account_details" value="{{ $entry->account->details }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="date_start">
                                            <strong>Date Start</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="date_start-{{ $entry->getKey() }}" name="date_start">
                                    </div>
                                    <div class="form-group">
                                        <label for="date_end">
                                            <strong>Date End</strong>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="date_end-{{ $entry->getKey() }}" name="date_end">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a 
                                        href="javascript:void(0)" 
                                        onclick="serviceInterrupt(this)" 
                                        data-button-type="serviceInterrupt"
                                        data-route="{{ url($crud->route.'/'.$entry->getKey().'/serviceInterrupt') }}" 
                                        data-account-id="{{ $entry->account->id }}"
                                        data-billing-id="{{ $entry->getKey() }}"
                                        class="btn btn-success"
                                    >
                                        <i class="las la-exclamation-triangle"></i> {{ __('Save') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>



                @endif

            
                @if($crud->hasAccess('sendNotification') && $entry->isMonthlyFee())
                    <li>
                        <a 
                            href="javascript:void(0)" 
                            onclick="sendNotificationEntry(this)" 
                            data-route="{{ url($crud->route.'/'.$entry->getKey().'/sendNotification') }}" 
                            data-resend="{{ $entry->notified_at }}"
                            class="btn btn-sm btn-link text-primary" 
                            data-button-type="sendNotification"
                            title="Dispatch Notification."
                            disabled="disabled"
                            >
                                <i class="las la-sms"></i>
                                {{ __('Send Notification') }}
                        </a>
                    </li>
                @endif
            

            </ul>
        </div>       
    @endif

@endif


{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif

<script>
    // Fix modal backdrop
    $(document).on('show.bs.modal', '.modal', function () {
        $(this).appendTo('body');
    });
</script>

<script>
    if (typeof serviceInterruptModal != 'function') {
        $("[data-button-type=serviceInterruptModal]").unbind('click');

        function serviceInterruptModal(button) {
            var billingId = button.getAttribute('data-billing-id');
            
            $('#serviceInterruptModal-'+billingId).modal('show');
        }
    }


    if (typeof serviceInterrupt != 'function') {
        $("[data-button-type=serviceInterrupt]").unbind('click');

        function serviceInterrupt(button) {
            var billingId = button.getAttribute('data-billing-id');
            var route = button.getAttribute('data-route');
            var accountId = button.getAttribute('data-account-id');
            // unique field
            var dateStart = $('#date_start-'+billingId).val();
            var dateEnd = $('#date_end-'+billingId).val();

            // console.log({
            //     billingId,
            //     route,
            //     dateStart,
            //     dateEnd,
            //     accountId
            // });

            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    date_start: dateStart,
                    date_end: dateEnd,
                    account_id: accountId 
                },
                success: function(result) {
                    // console.log('Success:', result);

                    if (typeof crud !== 'undefined') {
                        crud.table.ajax.reload();
                    }

                    if (result) {
                        new Noty({
                            type: "success",
                            text: result.msg
                        }).show();
                    }

                    // Close the modal
                    $('#serviceInterruptModal-'+billingId).modal('hide');

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
                    }
                }
            });

            
        }
        
    }





    if (typeof pay != 'function') {
        $("[data-button-type=pay]").unbind('click');

        function pay(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var button = $(button);
            var route = button.attr('data-route');

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
                                    swalError("There\'s been an error. Your item might not have been marked as paid.")
                                }			          	  
                            }
                        },
                        error: function(result) {
                            swalError("There\'s been an error. Your item might not have been marked as paid.")
                        }
                    });
                    }
                });
        }
    }


    if (typeof sendNotificationEntry != 'function') {
        $("[data-button-type=sendNotification]").unbind('click');

        function sendNotificationEntry(button) {
            // ask for confirmation before resending a notification
            var button = $(button);
            var route = button.attr('data-route');
            var resend = button.attr('data-resend');
            // Function to handle the AJAX request
            function sendNotificationRequest() {
                $.ajax({
                    url: route,
                    type: 'POST',
                    success: function(result) {
                        console.log(result);
                        if (result == 1) {
                            // Reload the CRUD table
                            if (typeof crud !== 'undefined') {
                                crud.table.ajax.reload();
                            }

                            // Show a success notification
                            new Noty({
                                type: "success",
                                text: "{!! '<strong>'.__('Notification Sent').'</strong><br>'.__('The bill was sent successfully.') !!}"
                            }).show();

                            // Hide any modal
                            $('.modal').modal('hide');
                        } else if (result.msg) {
                            // Show an error notification with the received message
                            new Noty({
                                type: "warning",
                                text: "{!! '<strong>'.__('Notification Not Sent').'</strong><br>' !!}" +result.msg
                            }).show();

                            swalError(result.msg);

                        } else {
                            swalError("There\'s been an error. Your item might not have been processed.");
                        }
                    },
                    error: function(result) {
                        swalError("There\'s been an error. Your item might not have been processed.");
                    }
                });
            }

            // Check if 'resend' is not empty
            if (!resend.trim()) {
                // If 'resend' is empty, directly trigger the AJAX request without showing SweetAlert
                sendNotificationRequest();
            } else {
                // If 'resend' is not empty, show SweetAlert confirmation
                swal({
                    title: "{!! trans('backpack::base.warning') !!}",
                    text: "{!! __('Are you sure you want to resend the notification?') !!}",
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
                            text: "{!! __('Resend') !!}",
                            value: true,
                            visible: true,
                            className: "bg-success",
                        },
                    },
                    dangerMode: true,
                }).then((value) => {
                    if (value) {
                        // Perform AJAX request
                        sendNotificationRequest();
                    }
                });
            }
        }
    }


    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('payEntry');
</script>
@if (!request()->ajax()) @endpush @endif