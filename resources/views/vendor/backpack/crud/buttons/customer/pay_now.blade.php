@if($crud->hasAccess('payNow') && $entry->isUnpaid())
    <a class="btn btn-sm btn-link"  href="{{ url($crud->route.'/'.$entry->getKey().'/pay-now') }}">
        <i class="las la-credit-card"></i>
        {{ __('Pay Now') }}
    </a>
@endif