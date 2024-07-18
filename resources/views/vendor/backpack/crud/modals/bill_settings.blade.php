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

                <p class="help-block text-danger">Note: For P2P and Fiber Day Start/End, if you select the 31st day and the billing month does not have a 31st day, or if it's February (28 days) or February in a leap year (29 days), it will automatically use the last day of the month.</p>
                
                {{-- FIBER --}}
                <div class="form-group text-info">
                    <label for="fiberGroup"><strong>Fiber Settings</strong></label>

                    <div class="row">
                        <div class="col-3">
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

                        <div class="col-3">
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

                        <div class="col-6">
                            <label for="fiber_day_cut_off">Day Cut Off</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="fiber_day_cut_off" name="fiber_day_cut_off">
                                <option value="">-</option>
                                <option {{ Setting::get('fiber_day_cut_off') == 0 ? "selected" : "" }} value="0">Same as the end of the billing period.</option>
                                @for($day = 1; $day <= 10; $day++)
                                    <option 
                                        {{ Setting::get('fiber_day_cut_off') == $day ? "selected" : "" }} 
                                        value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} after the end of the billing period.
                                    </option>
                                @endfor
                            </select>
                        </div>

                    </div>

                    
                    <div class="row mt-2">
                        <div class="col-12">
                            <label for="fiber_billing_period">Billing Period</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="fiber_billing_period" name="fiber_billing_period">
                                <option value="">-</option>

                                @foreach ([
                                    'previous_month_current_month' => 'Previous Month - Current Month',
                                    'current_month_current_month' => 'Current Month - Current Month',
                                    'current_month_next_month' => 'Current Month - Next Month',
                                ] as $value => $label)
                                    
                                    <option 
                                        {{ ($value == Setting::get('fiber_billing_period')) ? "selected" : "" }}
                                        value="{{ $value }}"
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>
                        </div>
                    </div>


                </div>
                
            

                {{-- P2P --}}
                <div class="form-group text-success">
                    <label for="p2pGroup"><strong>P2P Settings</strong></label>
                    <div class="row">
                        <div class="col-3">
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

                        <div class="col-3">
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

                        <div class="col-6">
                            <label for="p2p_day_cut_off">Day Cut Off</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="p2p_day_cut_off" name="p2p_day_cut_off">
                                <option value="">-</option>
                                <option {{ Setting::get('p2p_day_cut_off') == 0 ? "selected" : "" }} value="0">Same as the end of the billing period.</option>
                                @for($day = 1; $day <= 10; $day++)
                                    <option 
                                        {{ Setting::get('p2p_day_cut_off') == $day ? "selected" : "" }} 
                                        value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} after the end of the billing period.
                                    </option>
                                @endfor
                            </select>
                        </div>

                    </div>


                    <div class="row mt-2">
                        <div class="col-12">
                            <label for="p2p_billing_period">Billing Period</label>
                            <span class="text-danger">*</span>
                            <select class="form-control" id="p2p_billing_period" name="p2p_billing_period">
                                <option value="">-</option>

                                @foreach ([
                                    'previous_month_current_month' => 'Previous Month - Current Month',
                                    'current_month_current_month' => 'Current Month - Current Month',
                                    'current_month_next_month' => 'Current Month - Next Month',
                                ] as $value => $label)
                                    
                                    <option 
                                        {{ ($value == Setting::get('p2p_billing_period')) ? "selected" : "" }}
                                        value="{{ $value }}"
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>
                        </div>
                    </div>

                </div>
                



                <div class="form-group">
                    <label>
                        <strong>{{ __('Automate Process') }}</strong>
                    </label>
                    <br>
                    
                    <div class="form-check">
                        <input 
                            {{ Setting::get('enable_auto_bill') == 1 ? "checked" : ""  }}
                            class="form-check-input" type="checkbox" id="enable_auto_bill" name="enable_auto_bill">
                        <label class="form-check-label" for="enable_auto_bill">{{ __('Enable Auto Generate Bills') }}</label>
                    </div>

                    <div class="form-check">
                        <input 
                            {{ Setting::get('enable_auto_notification') == 1 ? "checked" : ""  }}
                            class="form-check-input" type="checkbox" id="enable_auto_notification" name="enable_auto_notification">
                        <label class="form-check-label" for="enable_auto_notification">{{ __('Enable Auto Send Notifications') }}</label>
                    </div>
                </div>





                

                <div class="form-group group_enable_auto_bill">
                    <label for="days_before_generate_bill">
                        <strong>When should the bill be auto-generated?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_generate_bill" name="days_before_generate_bill">
                        <option {{ Setting::get('days_before_generate_bill') == 0 ? "selected" : ""  }} value="0">Immediately at the end of the billing period.</option>
                        @for($day = 1; $day <= 10; $day++)
                            <option 
                                {{ Setting::get('days_before_generate_bill') == $day ? "selected" : ""  }}
                                value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} before the end of the billing period.
                            </option>
                        @endfor 

                    </select>
                </div>



                

                <div class="form-group group_enable_auto_notification">
                    <label for="days_before_send_bill_notification">
                        <strong>When should we send customer notifications?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_send_bill_notification" name="days_before_send_bill_notification">
                        <option {{ Setting::get('days_before_send_bill_notification') == "0" ? "selected" : ""  }} value="0">Immediately after the bill is created.</option>
                        @for($day = 1; $day <= 10; $day++)
                            <option 
                                {{ Setting::get('days_before_send_bill_notification') == $day ? "selected" : ""  }}
                                value="{{ $day }}">{{ $day }} {{ $day == 1 ? 'day' : 'days' }} after the bill is created.
                            </option>
                        @endfor 
                    </select>
                </div>
                

                <div class="form-group group_enable_auto_notification">
                    <label for="days_before_send_cut_off_notification">
                        <strong>When should we send cut-off notifications?</strong>
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control" id="days_before_send_cut_off_notification" name="days_before_send_cut_off_notification">
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
                <button type="button" class="btn btn-warning" 
                data-route="{{ route('billing.billSetting') }}"
                onclick="billSettings(this)">
                    <i class="las la-save"></i> Save
                </button>

            </div>
        </div>
    </div>
</div>













{{-- manual generate bill --}}
<div class="modal fade" id="generateBillModal" tabindex="-1" role="dialog" aria-labelledby="generateBillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateBillModalLabel">{{ __('Generate Bill') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

               


            <div class="form-group">
                <label><strong>{{ __('Account Subscription:') }}</strong></label>
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="form-check">
                            <input 
                                class="form-check-input" type="checkbox" id="fiber" name="generate_bill[]" value="fiber">
                            <label class="form-check-label" for="fiber">{{ __('FIBER') }}</label>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="form-check">
                            <input 
                                class="form-check-input" type="checkbox" id="p2p" name="generate_bill[]" value="p2p">
                            <label class="form-check-label" for="p2p">{{ __('P2P') }}</label>
                        </div>
                    </li>
                </ul>
            </div>
                


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" 
                    data-route="{{ route('billing.manualGenerateBill') }}"
                    onclick="manualGenerateBill(this)
                ">
                    <i class="las la-save"></i> Create
                </button>

            </div>
        </div>
    </div>
</div>


@include('crud::inc.loader')