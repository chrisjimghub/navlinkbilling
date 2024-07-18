@if ($crud->hasAccessToAny([
    'billSetting', 
    'manualGenerateBill', 
]))


<div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="las la-cog"></i>
        {{ ucfirst($crud->entity_name) .' Settings' }}
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        @if($crud->hasAccess('billSetting'))
            <li>
                <a href="javascript:void(0)" class="btn btn-sm btn-link" data-toggle="modal" data-target="#billSettingModal">
                    <i class="la la-cog"></i> 
                    {{ ucfirst($crud->entity_name) .' Settings' }}
                </a>
            </li>
            
        @endif

        @if ($crud->hasAccess('manualGenerateBill'))
            <li>
                <a href="javascript:void(0)" class="btn btn-sm btn-link" data-toggle="modal" data-target="#generateBillModal">
                    <i class="las la-plus"></i>
                    {{ __('Generate Bill') }}
                </a>
            </li>
        @endif    
    </ul>
</div>

@endif


@push('before_scripts')
    @include('crud::modals.bill_settings')
@endpush
