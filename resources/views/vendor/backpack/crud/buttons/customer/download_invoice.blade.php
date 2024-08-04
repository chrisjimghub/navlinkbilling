@if($crud->hasAccess('downloadInvoice'))
    <a class="btn btn-sm btn-link"  href="{{ url($crud->route.'/'.$entry->getKey().'/downloadInvoice') }}" target="_blank">
        <i class="las la-download"></i>
        {{ __('Download') }}
    </a>
@endif