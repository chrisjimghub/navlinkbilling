@if($crud->hasAccess('gcash') && $entry->isUnpaid())
    <a class="btn btn-sm btn-link"  href="{{ url($crud->route.'/'.$entry->getKey().'/gcashPay') }}">
        <i class="las la-credit-card"></i>
        {{ __('Gcash Pay') }}
    </a>
@endif