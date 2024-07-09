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
                            data-billing-id="{{ $entry->getKey() }}"
                            data-account-id="{{ $entry->account->id }}"
                            data-account-details="{{ $entry->account->details }}"
                            data-route="{{ url($crud->route.'/'.$entry->getKey().'/serviceInterrupt') }}"
                            >
                            <i class="las la-exclamation-triangle"></i> Service Interruption
                        </a>

                    </li>
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

{{-- NOTE:: all script i transfered it to the Operation as Widgets --}}
<script>

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('payEntry');
</script>
@if (!request()->ajax()) @endpush @endif