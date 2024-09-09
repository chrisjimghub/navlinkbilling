@if($crud->hasAccess('gcash') && !$entry->isPaid())
    <a class="btn btn-sm btn-link"  href="{{ url($crud->route.'/'.$entry->getKey().'/gcashPay') }}">
        <i class="las la-credit-card"></i>
        @if($entry->isPending())
            {{ __('app.gcash_button_pending') }}
        @else
            {{ __('app.gcash_button_pay') }}
        @endif
    </a>
@endif