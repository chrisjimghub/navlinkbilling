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
                        {{-- <span class="text-danger">*</span> --}}
                    </label>
                    <br>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="enable_auto_bill" name="enable_auto_bill">
                        <label class="form-check-label" for="item2">{{ __('Enable Auto Bill') }}</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="days_before_generate_bill">
                        <strong>How many days before the end of the billing period should the bill be generated?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_generate_bill" name="days_before_generate_bill">
                        <option>-</option>
                        @for($day = 1; $day <= 20; $day++)
                            <option value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} before the end of the billing period.</option>
                        @endfor 

                        <option value="0">Immediately at the end of the billing period.</option>
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
                                <option>-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="fiber_day_end">Day End</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="fiber_day_end" name="fiber_day_end">
                                <option>-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="fiber_billing_start">Billing Start</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="fiber_billing_start" name="fiber_billing_start">
                                <option>-</option>
                                <option value="previous_month">Previous Month</option>
                                <option value="current_month">Current Month</option>
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
                                <option>-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="p2p_day_end">Day End</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="p2p_day_end" name="p2p_day_end">
                                <option>-</option>

                                @for($day = 1; $day <= 31; $day++)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endfor 

                            </select>
                        </div>

                        <div class="col">
                            <label for="p2p_billing_start">Billing Start</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="p2p_billing_start" name="p2p_billing_start">
                                <option>-</option>
                                <option value="previous_month">Previous Month</option>
                                <option value="current_month">Current Month</option>
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
                        <option>-</option>
                        @for($day = 1; $day <= 20; $day++)
                            <option value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} after the bill is created.</option>
                        @endfor 
                        <option value="0">Immediately after the bill is created.</option>
                    </select>
                </div>
                

                <div class="form-group">
                    <label for="days_before_send_cut_off_notification">
                        <strong>How many days before the cut-off date should we send cut-off notifications to customers?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_send_cut_off_notification" name="days_before_send_cut_off_notification">
                        <option>-</option>
                        @for($day = 1; $day <= 3; $day++)
                            <option value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} before the cut-off date.</option>
                        @endfor 
                        <option value="0">Immediately on the cut-off date.</option>
                    </select>
                </div>






            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a 
                    href="javascript:void(0)" 
                    onclick="billSettings(this)" 
                    data-button-type="serviceInterrupt"
                    data-route="${button.getAttribute('data-route')}" 
                    data-billing-id="${billingId}"
                    class="btn btn-success"
                >
                    <i class="las la-save"></i> Save
                </a>
            </div>
        </div>
    </div>
</div>


@endpush