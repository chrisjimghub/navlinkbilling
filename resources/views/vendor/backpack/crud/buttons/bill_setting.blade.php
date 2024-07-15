@if ($crud->hasAccess('billSetting'))
    <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#billSettingModal">
        <i class="la la-cog"></i> 
        {{ ucfirst($crud->entity_name) .' Settings' }}
    </a>
@endif

@push('before_scripts')
    

<div class="modal fade" id="billSettingModal" tabindex="-1" role="dialog" aria-labelledby="billSettingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billSettingModalLabel">{{ __('Billing Settings') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>
                        <strong>{{ __('Auto Generate Bill') }}</strong>
                    </label>
                    <br>
                    
                    <div class="form-check">
                        <input 
                            {{ Setting::get('enable_auto_bill') == 1 ? "checked" : ""  }}
                            class="form-check-input" type="checkbox" id="enable_auto_bill" name="enable_auto_bill">
                        <label class="form-check-label" for="enable_auto_bill">{{ __('Enable Auto Bill') }}</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="days_before_generate_bill">
                        <strong>How many days before the end of the billing period should the bill be generated?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_generate_bill" name="days_before_generate_bill">
                        <option value="">-</option>
                        <option {{ Setting::get('days_before_generate_bill') == 0 ? "selected" : ""  }} value="0">Immediately at the end of the billing period.</option>
                        @for($day = 1; $day <= 20; $day++)
                            <option 
                                {{ Setting::get('days_before_generate_bill') == $day ? "selected" : ""  }}
                                value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} before the end of the billing period.
                            </option>
                        @endfor 

                    </select>
                </div>

                <p class="help-block text-success">Note: For P2P and Fiber Day Start/End, if you select the 31st day and the billing month does not have a 31st day, or if it's February (28 days) or February in a leap year (29 days), it will automatically use the last day of the month.</p>
                
                {{-- FIBER --}}
                <div class="form-group">
                    <label for="fiberGroup"><strong>Fiber Settings</strong></label>
                    <div class="row">
                        <div class="col">
                            <label for="fiber_day_start">Day Start</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="fiber_day_start" name="fiber_day_start">
                                <option value="">-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option 
                                        {{ Setting::get('fiber_day_start') == $day ? "selected" : ""  }}  
                                        value="{{ $day }}">{{ $day }}
                                    </option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="fiber_day_end">Day End</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="fiber_day_end" name="fiber_day_end">
                                <option value="">-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option 
                                        {{ Setting::get('fiber_day_end') == $day ? "selected" : ""  }}
                                        value="{{ $day }}">{{ $day }}
                                    </option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="fiber_billing_start">Billing Start</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="fiber_billing_start" name="fiber_billing_start">
                                <option value="">-</option>
                                <option {{ Setting::get('fiber_billing_start') == "previous_month" ? "selected" : ""  }} value="previous_month">Previous Month</option>
                                <option {{ Setting::get('fiber_billing_start') == "current_month" ? "selected" : ""  }} value="current_month">Current Month</option>
                            </select>
                        </div>

                    </div>
                </div>
                
                
                {{-- P2P --}}
                <div class="form-group">
                    <label for="p2pGroup"><strong>P2P Settings</strong></label>
                    <div class="row">
                        <div class="col">
                            <label for="p2p_day_start">Day Start</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="p2p_day_start" name="p2p_day_start">
                                <option value="">-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option 
                                        {{ Setting::get('p2p_day_start') == $day ? "selected" : ""  }}
                                        value="{{ $day }}">{{ $day }}
                                    </option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="p2p_day_end">Day End</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="p2p_day_end" name="p2p_day_end">
                                <option value="">-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option 
                                        {{ Setting::get('p2p_day_end') == $day ? "selected" : ""  }}
                                        value="{{ $day }}">{{ $day }}
                                    </option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="p2p_billing_start">Billing Start</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="p2p_billing_start" name="p2p_billing_start">
                                <option value="">-</option>
                                <option {{ Setting::get('p2p_billing_start') == "previous_month" ? "selected" : ""  }} value="previous_month">Previous Month</option>
                                <option {{ Setting::get('p2p_billing_start') == "current_month" ? "selected" : ""  }} value="current_month">Current Month</option>
                            </select>
                        </div>

                    </div>
                </div>
                
                

                <div class="form-group">
                    <label for="days_before_send_bill_notification">
                        <strong>How many days after the bill is created should we send notifications to customers?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_send_bill_notification" name="days_before_send_bill_notification">
                        <option value="">-</option>
                        <option {{ Setting::get('days_before_send_bill_notification') == "0" ? "selected" : ""  }} value="0">Immediately after the bill is created.</option>
                        @for($day = 1; $day <= 20; $day++)
                            <option 
                                {{ Setting::get('days_before_send_bill_notification') == $day ? "selected" : ""  }}
                                value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} after the bill is created.
                            </option>
                        @endfor 
                    </select>
                </div>
                

                <div class="form-group">
                    <label for="days_before_send_cut_off_notification">
                        <strong>How many days before the cut-off date should we send cut-off notifications to customers?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_send_cut_off_notification" name="days_before_send_cut_off_notification">
                        <option value="">-</option>
                        <option {{ Setting::get('days_before_send_cut_off_notification') == "0" ? "selected" : ""  }} value="0">Immediately on the cut-off date.</option>
                        @for($day = 1; $day <= 3; $day++)
                            <option 
                                {{ Setting::get('days_before_send_cut_off_notification') == $day ? "selected" : ""  }}
                                value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} before the cut-off date.
                            </option>
                        @endfor 
                    </select>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" 
                data-route="{{ route('billing.billSetting') }}"
                onclick="billSettings(this)">
                    <i class="las la-save"></i> Save
                </button>

            </div>
        </div>
    </div>
</div>

@endpush

@push('after_scripts')
<script>
if (typeof billSettings != 'function') {

    function billSettings(button) {
        var route = button.getAttribute('data-route');

        // Collect data from modal inputs
        var formData = {
            enable_auto_bill: $('#enable_auto_bill').prop('checked') ? 1 : 0,
            days_before_generate_bill: $('#days_before_generate_bill').val(),
            fiber_day_start: $('#fiber_day_start').val(),
            fiber_day_end: $('#fiber_day_end').val(),
            fiber_billing_start: $('#fiber_billing_start').val(),
            p2p_day_start: $('#p2p_day_start').val(),
            p2p_day_end: $('#p2p_day_end').val(),
            p2p_billing_start: $('#p2p_billing_start').val(),
            days_before_send_bill_notification: $('#days_before_send_bill_notification').val(),
            days_before_send_cut_off_notification: $('#days_before_send_cut_off_notification').val()
        };

        // Send AJAX POST request
        $.ajax({
            type: 'POST',
            url: route, 
            data: formData,
            success: function(response) {
                // console.log(response)

                if (response.msg) {
                    new Noty({
                        text: response.msg,
                        type: 'success'
                    }).show();
                }

                // Handle success response, if needed
                // console.log('Form data submitted successfully:', response);
                $('#billSettingModal').modal('hide'); // Close the modal
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
                    new Noty({
                        text: 'Error submitting form. Please try again.',
                        type: 'error'
                    }).show();
                }
            }
        });
    }


}
</script>
@endpush