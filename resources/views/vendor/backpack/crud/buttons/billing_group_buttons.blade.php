@if ($crud->hasAccessToAny([
        'pay', 
        'payUsingCredit', 
        'upgradePlan',
        'serviceInterrupt',
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
                            onclick="payEntry(this)" 
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
                @if($crud->hasAccess('buttonUpgradePlan'))
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


                @if($crud->hasAccess('buttonServiceInterruption'))
                    <li>
                        <a 
                            href="javascript:void(0)" 
                            {{-- onclick="payEntry(this)"  --}}
                            {{-- data-route="{{ url($crud->route.'/'.$entry->getKey().'/pay') }}"  --}}
                            class="btn btn-sm btn-link text-danger" 
                            {{-- data-button-type="pay" --}}
                            {{-- title="Marked as paid?" --}}
                            >
                                <i class="las la-exclamation-triangle"></i>
                                {{ __('Service Interruption') }}
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
    if (typeof payEntry != 'function') {
        $("[data-button-type=pay]").unbind('click');

        function payEntry(button) {
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
    // crud.addFunctionToDataTablesDrawEventQueue('payEntry');
</script>
@if (!request()->ajax()) @endpush @endif